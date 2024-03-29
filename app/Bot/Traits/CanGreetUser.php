<?php

namespace App\Bot\Traits;

use App\Models\User;
use Illuminate\Support\Str;

trait CanGreetUser
{
    /**
     * Determine whether the $message is saying hello.
     */
    public static function isSayingHello(string $message): bool
    {
        return Str::contains(strtolower($message), ['halo', 'hello', 'hai']);
    }

    /**
     * Greet the user.
     */
    public static function greetUser(string $message, string $userID): string
    {
        // Send the greeting response
        $user = User::firstWhere('psid', $userID);
        $name = $user->first_name;

        return __('bot.greet_user', compact('name'));
    }
}
