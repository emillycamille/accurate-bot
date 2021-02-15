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
        ]);
        if ($response['cod'] === 200) {
            return "Cuaca di kota {$response['name']} adalah {$response['weather'][0]['description']} dengan suhu {$response['main']['temp']} ºC";
        }

        return 'Cuaca tidak ditemukan.';
    }
}
