<?php

namespace App\Bot\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

trait CanGetStarted
{
    public static function getStarted(string $userID): void
    {
        $response = Http::get(config('bot.fb_api_url') . $userID, [
            'access_token' => config('bot.fb_page_token'),
        ])->throw();

        // Save user's first name and last name
        $data = Arr::only($response->json(), []);
        $data['fb_firstname'] = $response->json('first_name');
        $data['fb_lastname'] = $response->json('last_name');

        User::updateOrCreate(['psid' => $userID], $data);

        $name = $data['fb_firstname'];
        $message = __('bot.get_started_message', compact('name'));

        static::sendMessage($message, $userID);
    }
}
