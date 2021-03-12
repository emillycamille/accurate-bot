<?php

namespace App\Bot\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\User;

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
        $response = Http::get(config('bot.fb_api_url') . $userID, [
            'access_token' => config('bot.fb_page_token'),
        ])->throw();

        // Save user's first name and last name
        $data['fb_firstname'] = $response->json('first_name');
        $data['fb_lastname'] = $response->json('last_name');

        User::updateOrCreate(['psid' => $userID], $data);

        // Send the greeting response

        $name = $response['first_name'];;

        return __('bot.greet_user', compact('name'));
    }
}
