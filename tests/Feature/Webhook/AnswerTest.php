<?php

use Illuminate\Support\Facades\Http;

test('bot can tell first president of Indonesia', function () {
    Http::fake([
        config('bot.serp_api_url').'*' => Http::response([
            'answer_box' => [
                'answers' => [
                    ['answer' => 'TEST_ANSWER'],
                ],
            ],
        ]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('manusia pertama di bulan');

    $this->assertRequestSent();
})->skip();

test('bot can\'t find answer', function () {
    Http::fake([
        config('bot.serp_api_url').'*' => Http::response([]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('tralala trilili');

    $this->assertRequestSent();
})->skip();
