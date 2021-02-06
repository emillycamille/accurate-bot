<?php

namespace App\Bot\Traits;

use Illuminate\Support\Str;

trait CanTellTime
{
    public static function isAskingTime(string $message): bool
    {
        return Str::contains($message, ['hari', 'jam']);
    }

    public static function tellTime(string $message): string
    {
        $reply = '';

        if (Str::contains($message, 'hari')) {
            $reply .= now()->format('l ');
        }

        if (Str::contains($message, 'jam')) {
            $reply .= now()->format('H:i');
        }

        return $reply;
    }
}
