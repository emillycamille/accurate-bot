<?php

namespace App\Bot\Traits;

use Illuminate\Support\Str;

trait CanConnectAccurate
{
    public static function isLoginRequest(string $message): bool
    {
        return Str::contains($message, ['login', 'Login']);
    }

    public static function sendLoginButton(): string
    {
        return 'login';
    }
}
