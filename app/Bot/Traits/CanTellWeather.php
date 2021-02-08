<?php

namespace App\Bot\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

trait CanTellWeather
{
    /**
     * Determine whether the $message is asking weather.
     */
    public static function isAskingWeather(string $message): bool
    {
        return Str::contains($message, ['cuaca','Cuaca']);
    }

    /**
     * Tell the current time, as requested in $message.
     */
    public static function tellWeather(string $message): string
    {
    $messageSplit = preg_split("/\s+/", $message);
    $city = end($messageSplit);
    $weatherKey = env('WEATHER_API_KEY');

    // $response = Http::get("api.openweathermap.org/data/2.5/weather",
    // [
    //     'q' => $city,
    //     'units' => 'metric',
    //     'appid' => $weatherKey,
    // ]);
    //     dd(json_decode(file_get_content($response),true));

    $json = json_decode(file_get_contents("http://api.openweathermap.org/data/2.5/weather?q={$city}&units=metric&lang=id&appid={$weatherKey}"),true);
        if ($json['cod'] === '404') {
            return "Kota tidak ditemukan";
        } else {
        return ($json['weather'][0]['description']);
        }
    }
}
