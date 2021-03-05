<?php

namespace App\Bot\Traits;

use Illuminate\Support\Str;

trait CanTellTime
{
    /**
     * Determine whether the $message is asking time.
     */
    public static function isAskingTime(string $message): bool
    {
        return Str::contains(strtolower($message), ['hari', 'jam']);
    }

    /**
     * Tell the current time, as requested in $message.
     */
    public static function tellTime(string $message): string
    {
        $message = strtolower($message);
        $reply = '';

        if (Str::contains($message, 'hari')) {
            $reply .= now()->dayName.' ';
        }

        if (Str::contains($message, 'jam')) {
            $reply .= now()->format('H:i');
        }

        return $reply;
    }
}
