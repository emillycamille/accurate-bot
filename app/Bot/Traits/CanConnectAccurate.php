<?php

namespace App\Bot\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait CanConnectAccurate
{
    public static function getAccessToken(string $code): void
    {
        $authorization = 'Basic '.base64_encode(
            config('accurate.client_id').':'.config('accurate.client_secret'),
        );

        $response = Http::withHeaders([
            'Authorization' => $authorization,
        ])->post('https://account.accurate.id/oauth/token', [
            'code' => $code,
            'grant_type' => 'authorization_code',
        ]);
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
