<?php

use Illuminate\Support\Facades\Http;


test('bot can send confirmation', function () {

    Http::fake([
        config('bot.translate_api_url').'*' => Http::response([
            'status' => true,
            'message' => 'success',
            'data' => ['result' => 'tomorrow at 10:00'],
        ]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('remind makan - Besok pukul 10:00');

    $this->assertRequestSent();
});

test('bot can return exception', function () {

    Http::fake([
        config('bot.translate_api_url').'*' => Http::response([
            'status' => true,
            'message' => 'success',
            'data' => ['result' => 'tomorrow at 10'],
        ]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('remind makan - Besok pukul 10');

    $this->assertRequestSent();
});
