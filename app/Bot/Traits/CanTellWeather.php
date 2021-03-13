<?php

namespace App\Bot\Traits;

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

        $response = Http::get(config('bot.weather_api_url'), [
            'q' => $city,
            'units' => 'metric',
            'lang' => 'id',
            'appid' => config('bot.weather_api_key'),
        ])->throw();

        if ($response['cod'] === 200) {
            $cityName = $response['name'];
            $weatherDescription = $response['weather'][0]['description'];
            $temp = $response['main']['temp'];

            return __(
                'bot.weather_reply',
                compact('cityName', 'weatherDescription', 'temp')
            );
        } else {
            return __('bot.city_not_found');
        }
    }
}
