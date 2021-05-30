<?php

use Illuminate\Support\Facades\Http;

test('bot can tell weather of a city', function () {
    Http::fake([
        config('bot.weather_api_url').'*' => Http::response([
            'cod' => 200,
            'weather' => [['description' => 'TEST_WEATHER_RESULT']],
            'name' => 'CITY_NAME',
            'main' => ['temp' => 'TEMPERATURE'],
        ]),
    ]);

    $this->assertReceiveAction(
        'getWeather',
        ['city' => 'Eindhoven'],
        'Sekarang di Eindhoven lagi :description dengan temperatur :temperature derajat',
    );
});

test('bot can return error message for unavailable city', function () {
    Http::fake([
        config('bot.weather_api_url').'*' => Http::response(null, 404),
    ]);

    $this->assertReceiveAction(
        'getWeather',
        ['city' => 'Eindhoven'],
        'Sekarang di Eindhoven lagi :description dengan temperatur :temperature derajat',
    );
});
