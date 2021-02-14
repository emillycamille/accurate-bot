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
        return Str::contains($message, ['cuaca', 'Cuaca']);
    }

    /**
     * Tell the current time, as requested in $message.
     */
    public static function tellWeather(string $message): string
    {
        $messageSplit = preg_split("/\s+/", $message);
        $city = end($messageSplit);
        $weatherKey = env('WEATHER_API_KEY');

        $response = Http::get('http://api.openweathermap.org/data/2.5/weather', [
            'q' => $city,
            'units' => 'metric',
            'appid' => $weatherKey,
        ]);

        return data_get($response, 'weather.0.description', 'Cuaca tidak ditemukan.');
    }
}
