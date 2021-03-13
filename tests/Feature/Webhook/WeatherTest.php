<?php

use Illuminate\Support\Facades\Http;

test('bot can tell weather at Jakarta', function () {
    Http::fake([
        config('bot.weather_api_url').'*' => Http::response([
            'cod' => 200,
            'weather' => [['description' => 'TEST_WEATHER_RESULT']],
            'name' => 'CITY_NAME',
            'main' => ['temp' => 'TEMPERATURE'],
        ]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('    cuaca   sekarang di kota  jakarta   ');

    $this->assertRequestSent();
});

test('bot cannot tell unavailable weather', function () {
    Http::fake([
        config('bot.weather_api_url').'*' => Http::response([
            'cod' => 404,
        ]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('cuaca di jumanji');

    $this->assertRequestSent();
});
