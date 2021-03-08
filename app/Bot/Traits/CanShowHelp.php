<?php

namespace App\Bot\Traits;

use Illuminate\Support\Str;

trait CanShowHelp
{
    /**
     * Determine whether the $message is asking for help.
     */
    public static function isAskingHelp(string $message): bool
    {
        return Str::contains(strtolower($message), ['help']);
    }

    /**
     * Tell available functions, as requested in $message.
     */
    public static function tellHelp(string $message): string
    {

        $reply = __('bot.available_functions');
        return $reply;
    }
}
