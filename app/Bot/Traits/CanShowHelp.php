<?php

namespace App\Bot\Traits;

trait CanShowHelp
{
    /**
     * Determine whether the $message is asking for help.
     */
    public static function isAskingHelp(string $message): bool
    {
        return strtolower($message) == 'help';
    }

    /**
     * Tell available functions, as requested in $message.
     */
    public static function tellHelp(): string
    {
        $reply = __('bot.available_functions')."\n\n";
        $count = 1;

        foreach (__('bot.abilities') as $key => $value) {
            $reply .= sprintf('%d. ', $count);
            $reply .= $value;
            $reply .= "\n\n";
            $count++;
        }

        return $reply;
    }
}
