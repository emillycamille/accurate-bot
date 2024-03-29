<?php

namespace App\Bot\Traits;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

trait CanGetWeather
{
    /**
     * Tell the current weather, as requested in $message.
     */
    public static function getWeather(array $params, string $template): string
    {
        try {
            $response = Http::get(config('bot.weather_api_url'), [
                'q' => $params['city'],
                'units' => 'metric',
                'lang' => 'id',
                'appid' => config('bot.weather_api_key'),
            ])->throw();

            $description = $response['weather'][0]['description'];
            $temperature = $response['main']['temp'];

            $message = make_replacements($template, compact('description', 'temperature'));
        } catch (RequestException $e) {
            if ($e->getCode() === 404) {
                $message = __('bot.city_not_found');
            }
        }

        return $message;
    }
}
