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
    public static function askAccurate(string $psid, string $uri): array
    {
        $user = User::firstWhere('psid', $psid);

        $url = config('accurate.api_url').$uri;

        $response = Http::withToken($user->access_token)->get($url);

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
        static::sendMessage(__('auth.login_successful', compact('name')), $psid);
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
    public static function askWhichDb(string $psid)
    {
        $dbs = static::askAccurate($psid, 'db-list.do')['d'];

        $payload = static::makeButtonPayload(__('common.choose_db'), array_map(function ($db) {
            return [
                'type' => 'postback',
                'title' => $db['alias'],
                'payload' => $db['id'],
            ];
        }, $dbs));

        static::sendMessage($payload, $psid);
    }

    /**
     * Return payload that will send login button to user.
     */
    public static function sendLoginButton(string $psid): array
    {
        // Accurate should redirect back to this app carrying the PSID, so we can
        // associate the PSID with the Accurate access token.
        $redirect_uri = config('accurate.redirect_url')
            .'?'.http_build_query(compact('psid'));

        $url = config('accurate.login_url')
            .'&'.http_build_query(compact('redirect_uri'));

        return static::makeButtonPayload('Login to Accurate', [
            [
                'type' => 'web_url',
                'title' => 'Login',
                'url' => $url,
                'webview_height_ratio' => 'tall',
            ],
        ]);
    }
}
