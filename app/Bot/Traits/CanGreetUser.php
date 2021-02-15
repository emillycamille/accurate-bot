<?php

namespace App\Bot\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait CanGreetUser
{
    /**
     * Determine whether the $message is asking time.
     */
    public static function isSayingHello(string $message): bool
    {
        return Str::contains($message, ['Halo', 'halo', 'Hi', 'hi']);
    }

    /**
     * Tell the current time, as requested in $message.
     */
    public static function greetUser(string $message, $event): string
    {
        $userID = $event['sender']['id'];

        // Should use Http::get.
        $response = Http::get(config('bot.fb_user_url').$userID, [
            'access_token' => config('bot.fb_page_token'),

        ]);

        $name = $response['first_name'];

        return "Halo {$name}!";
    }
}
