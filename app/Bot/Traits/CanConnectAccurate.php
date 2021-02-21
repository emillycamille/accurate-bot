<?php

namespace App\Bot\Traits;

use Illuminate\Support\Str;

trait CanConnectAccurate
{
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
                'url' => config('bot.accurate_login_url'),
                'webview_height_ratio' => 'tall',
            ],
        ]);
    }
}
