<?php

namespace App\Bot\Traits;

use Illuminate\Support\Str;

trait CanConnectAccurate
{
    public static function isLoginRequest(string $message): bool
    {
        return Str::contains($message, ['login', 'Login']);
    }

    public static function sendLoginButton(): array
    {
        return [
            'attachment' => [
                'type' => 'template',
                'payload' => [
                    'template_type' => 'button',
                    'text' => 'Login to Accurate',
                    'buttons' => [
                        [
                            'type' => 'web_url',
                            'title' => 'Login',
                            'url' => config('bot.accurate_login_url'),
                            'webview_height_ratio' => 'tall',
                        ],
                    ],
                ],
            ],
        ];
    }
}
