<?php

namespace App\Bot\Traits;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait CanConnectAccurate
{
    /**
     * Make GET request to Accurate, to retrieve information.
     */
    public static function askAccurate(string $psid, string $uri, array $query = null): ?array
    {
        $user = User::firstWhere('psid', $psid);

        // If the psid is unrecognized, we should ask the user to login to Accurate.
        if (! $user) {
            static::sendLoginButton($psid);

            return null;
        }

        // If we want to request basic API, use api_url in config.
        // Else, use the user's host.
        $url = (! Str::contains($uri, '/'))
            ? config('accurate.api_url')
            : $user->host.'/api/';

        $url .= $uri;

        $response = Http::withToken($user->access_token)
            ->withHeaders(['X-Session-ID' => $user->session])
            ->get($url, $query)
            ->throw();

        return $response->json();
    }

    /**
     * Get access token from Accurate and store it with the user data.
     */
    public static function getAccessToken(string $code, string $psid): void
    {
        // Get access token from Accurate API.
        $response = Http::withBasicAuth(
            config('accurate.client_id'),
            config('accurate.client_secret'),
        )->asForm()->post(config('accurate.access_token_url'), [
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => config('accurate.redirect_url').'?'.http_build_query(compact('psid')),
        ])->throw();

        // Get access token and user data from the response.
        $name = $response->json('user.name');
        $data = Arr::only($response->json(), ['access_token', 'refresh_token']);
        $data['email'] = $response->json('user.email');
        $data['name'] = $name;

        // Save the data to the `users` table.
        User::updateOrCreate(['psid' => $psid], $data);

        // Send message to user that the login is successful.
        static::sendMessage(__('bot.login_successful', compact('name')), $psid);
    }

    /**
     * Determine whether the $message is requesting to login.
     */
    public static function isRequestingLogin(string $message): bool
    {
        return Str::contains($message, ['login', 'Login']);
    }

    /**
     * Ask user to choose which DB they want to open, by sending postbacks.
     */
    public static function askWhichDb(string $psid): void
    {
        $dbs = static::askAccurate($psid, 'db-list.do')['d'];

        // Send postback buttons so user can choose which DB to open.
        $payload = static::makeButtonPayload(
            __('bot.choose_db'),
            array_map(function ($db) use ($psid) {
                return [
                    'type' => 'postback',
                    'title' => $db['alias'],

                    // We should always include the $psid as the second payload,
                    // because FB won't include it in the `messaging_postback` event.
                    'payload' => "OPEN_DB:$psid:{$db['id']}",
                ];
            }, $dbs)
        );

        static::sendMessage($payload, $psid);
    }

    public static function isAskingItemList(string $message): bool
    {
        return Str::contains(strtolower($message), ['barang', 'list']);
    }

    public static function listItem(string $psid): string
    {
        $items = static::askAccurate($psid, 'item/list.do', [
            'fields' => 'name,availableToSell,unitPrice',
        ])['d'];

        $string = sprintf(
            '%s:\n %s\n %s\n %s\n',
            __('bot.list_item_title'),
            '---------------------',
            'Nama     Harga     Stok',
            '---------------------',
        );

        foreach ($items as $i => $item) {
            $string .= sprintf(
                '%d. %s %s %s\n',
                $i + 1,
                $item['name'],
                'Rp'.$item['unitPrice'],
                'Stok: '.$item['availableToSell'],
            );
        }

        return $string;
    }

    /**
     * Open an Accurate DB and save the host and session data.
     */
    public static function openDb(string $psid, string $id): void
    {
        $data = static::askAccurate($psid, 'open-db.do', compact('id'));

        if ($data) {
            // Save the host and session to DB, because they will be needed
            // for the next Accurate requests.
            User::where('psid', $psid)->update([
                'host' => $data['host'],
                'session' => $data['session'],
            ]);

            static::sendMessage(__('bot.db_opened'), $psid);
        }
    }

    /**
     * Return payload that will send login button to user.
     */
    public static function sendLoginButton(string $psid): void
    {
        // Accurate should redirect back to this app carrying the PSID, so we can
        // associate the PSID with the Accurate access token.
        $redirect_uri = config('accurate.redirect_url')
            .'?'.http_build_query(compact('psid'));

        $url = config('accurate.login_url')
            .'&'.http_build_query(compact('redirect_uri'));

        $payload = static::makeButtonPayload('Login to Accurate', [
            [
                'type' => 'web_url',
                'title' => 'Login',
                'url' => $url,
                'webview_height_ratio' => 'tall',
            ],
        ]);

        static::sendMessage($payload, $psid);
    }
}
