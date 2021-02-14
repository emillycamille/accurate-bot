<?php

use Illuminate\Support\Facades\Http;

test('bot can tell weather at Jakarta', function () {
    Http::fake([
        'http://api.openweathermap.org/*' => Http::response([
            'weather' => [['description' => 'TEST_WEATHER_RESULT']],
        ]),
    ]);

    $this->receiveMessage('    cuaca   sekarang di kota  jakarta   ');

    $this->assertRequestSent();
});
