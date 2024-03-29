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
     * Determine whether the $message is number from 1-13.
     */
    public static function isSendingNumber(string $message): bool
    {
        for ($i = 1; $i < 14; $i++) {
            if ($message == $i) {
                return true;
            }
        }

        return false;
    }

    /**
     * Tell available functions, as requested in $message.
     */
    public static function tellHelp(string $psid): void
    {
        static::sendMessage(__('bot.video_tutorial'), $psid);
        $reply = __('bot.available_functions')."\n\n";
        $count = 1;

        foreach (__('bot.abilities') as $key => $value) {
            $reply .= sprintf('%d. ', $count);
            $reply .= $value;
            $reply .= "\n\n";
            $count++;
        }

        static::sendMessage($reply, $psid);
        static::sendMessage(__('bot.quick_reply_explanation'), $psid);
    }
}
