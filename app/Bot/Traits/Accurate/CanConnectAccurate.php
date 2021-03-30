<?php

namespace App\Bot\Traits\Accurate;

use App\Models\User;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait CanConnectAccurate
{
    use CanManageCustomers,
        CanManageItems,
        CanManageSales,
        CanManagePurchases,
        CanManageDb;

    /**
     * Make GET request to Accurate, to retrieve information.
     */
    public static function askAccurate(string $psid, string $uri, array $query = null): ?array
    {
        // 1. Check that a user with this psid is found in our db.
        // --------------------------------------------------------------------
        $user = User::firstWhere('psid', $psid);

        // If not found, we should ask the user to login to Accurate.
        if ((!$user) || (!$user->access_token)) {
            static::sendLoginButton($psid);

            return null;
        }

        // 2. If the request is not basic, ensure that the user has session.
        // --------------------------------------------------------------------
        $isBasic = (!Str::contains($uri, '/'));

        // If request is not basic, and user has no session or no host, ask which
        // db they want to open.
        if (!$isBasic && ((!$user->session) || (!$user->host) || (!$user->database_id))) {
            static::askWhichDb($psid);

            return null;
        }

        // 3. Make the request to Accurate.
        // --------------------------------------------------------------------

        // Depending on the basicness of the request, determine the host url.
        $url = $isBasic ? config('accurate.api_url') : $user->host . '/accurate/api/';
        $url .= $uri;

        Log::debug("askAccurate: $psid: $url", $query ?? []);

        try {
            $response = Http::withToken($user->access_token)
                ->withHeaders(['X-Session-ID' => $user->session])
                ->get($url, $query)
                ->throw()
                ->json();
        } catch (RequestException $e) {
            // If unauthorized or access token invalid, send login button.
            if (in_array($e->response->json('error'), ['unauthorized', 'invalid_token'])) {
                static::sendLoginButton($psid);

                return null;
            }

            // If session is invalid, reopen db and reask Accurate.
            if ($e->response->json('s') === false && $user->session && $user->database_id) {
                $dbid = $user->database_id;

                // Nullify user's database_id to prevent infinite loop. If this second
                // request fails, the user will be asked which DB to open.
                $user->update(['database_id' => null]);

                static::openDb($psid, $dbid);

                return static::askAccurate($psid, $uri, $query);
            }

            throw $e;
        }

        Log::debug('fromAccurate:', ($response ?? []) + ["\n"]);

        return $response;
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
            'redirect_uri' => config('accurate.redirect_url') . '?' . http_build_query(compact('psid')),
        ])->throw();

        // Get access token and user data from the response.
        $name = $response->json('user.name');
        $data = Arr::only($response->json(), ['access_token', 'refresh_token']);
        $data['email'] = $response->json('user.email');
        $data['accurate_name'] = $name;

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
        return Str::contains(strtolower($message), ['login']);
    }

    /**
     * Return payload that will send login button to user.
     */
    public static function sendLoginButton(string $psid): void
    {
        // Accurate should redirect back to this app carrying the PSID, so we can
        // associate the PSID with the Accurate access token.
        $redirect_uri = config('accurate.redirect_url')
            . '?' . http_build_query(compact('psid'));

        $url = config('accurate.login_url')
            . '&' . http_build_query(compact('redirect_uri'));

        $payload = static::makeButtonPayload('Sambungkan dengan Accurate', [
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
