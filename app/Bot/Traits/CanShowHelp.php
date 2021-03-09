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
        return $message == 'help';
    }

    /**
     * Tell available functions, as requested in $message.
     */
    public static function tellHelp(): string
    {
        $reply = __('bot.available_functions')."\n\n";
        $function_list = array_values(__('bot.function_list'));

        for ($i=0; $i < count($function_list); $i++) { 
            $reply .= sprintf("%d. ",$i+1);
            $reply .= $function_list[$i];
            $reply .= "\n";
        }
            
        return $reply;
    }
}
