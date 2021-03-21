<?php

namespace App\Bot\Traits;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

trait CanGetStarted
{
    public static function facebookWelcome(string $psid): void
    {
        $response = Http::get(config('bot.fb_api_url').$psid, [
            'access_token' => config('bot.fb_page_token'),
        ])->throw();

        // Save user's first name and last name
        $data = Arr::only($response->json(), ['first_name', 'last_name']);

        User::updateOrCreate(['psid' => $psid], $data);

        $message = __('bot.get_started_message', ['name' => $data['first_name']]);

        static::sendMessage($message, $psid);
    }
}
