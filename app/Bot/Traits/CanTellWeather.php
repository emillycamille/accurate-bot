<?php

namespace App\Bot\Traits;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait CanTellWeather
{
    /**
     * Determine whether the $message is asking weather.
     */
    public static function isAskingWeather(string $message): bool
    {
        return Str::contains(strtolower($message), ['cuaca']);
    }

    /**
     * Tell the current weather, as requested in $message.
     */
    public static function tellWeather(string $message): string
    {
        $messageSplit = preg_split("/\s+/", $message);
        $city = end($messageSplit);

        try {
            $response = Http::get(config('bot.weather_api_url'), [
                'q' => $city,
                'units' => 'metric',
                'lang' => 'id',
                'appid' => config('bot.weather_api_key'),
            ])->throw();
        } catch (RequestException $e) {
            if ($e->getCode() === 404) {
                return __('bot.city_not_found');
            }
        }

        $city = $response['name'];
        $description = $response['weather'][0]['description'];
        $temp = $response['main']['temp'];

        return __(
            'bot.weather_reply',
            compact('city', 'description', 'temp'),
        );
    }
}
