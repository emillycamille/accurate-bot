<?php

namespace App\Bot\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait CanGreetUser
{
    /**
     * Determine whether the $message is saying hello.
     */
    public static function isSayingHello(string $message): bool
    {
        return Str::contains(strtolower($message), ['halo', 'hello']);
    }

    /**
     * Greet the user.
     */
    public static function greetUser(string $message, string $userID): string
    {
        $response = Http::get(config('bot.fb_api_url').$userID, [
            'access_token' => config('bot.fb_page_token'),
        ])->throw();

        $name = $response['first_name'];

        return "Halo {$name}!";
    }
}
