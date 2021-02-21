<?php

namespace App\Bot\Traits;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;

trait CanConnectAccurate
{
    public static function getAccessToken(string $code): View
    {
        // Get access token from Accurate API.
        $response = Http::withBasicAuth(
            config('accurate.client_id'),
            config('accurate.client_secret'),
        )->asForm()->post(config('accurate.access_token_url'), [
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => 'https://accurate-bot.herokuapp.com/accurate-callback',
        ]);

        if ($response->failed()) {
            // TODO: create proper view.
            return view('error');
        }

        // Get access token and user data from the response.
        $data = Arr::only($response->json(), ['access_token', 'refresh_token']);
        $data['name'] = $response->json('user.name');

        // Save the data to the `users` table.
        User::updateOrCreate([
            'email' => $response->json('user.email'),
        ], $data);

        // TODO: create proper view.
        return view('success');
    }

    /**
     * Determine whether the $message is requesting to login.
     */
    public static function isRequestingLogin(string $message): bool
    {
        return Str::contains($message, ['login', 'Login']);
    }

    /**
     * Return payload that will send login button to user.
     */
    public static function sendLoginButton(): array
    {
        return static::makeButtonPayload('Login to Accurate', [
            [
                'type' => 'web_url',
                'title' => 'Login',
                'url' => config('accurate.login_url'),
                'webview_height_ratio' => 'tall',
            ],
        ]);
    }
}
