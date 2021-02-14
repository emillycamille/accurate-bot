<?php

namespace App\Bot\Traits;

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
    public static function greetUser(string $message, string $userID): string
    {
        if ($userID === 'PS_ID') {
            return 'Halo bro';
        } else {
            $fbPageToken = env('FB_PAGE_TOKEN');
            // Should use Http::get.
            $json = json_decode(file_get_contents("https://graph.facebook.com/v3.2/{$userID}?access_token={$fbPageToken}"), true);
            $name = $json['first_name'];

            return "Halo {$name}!";
        }
    }
}
