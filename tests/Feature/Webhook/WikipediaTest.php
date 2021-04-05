<?php

use Illuminate\Support\Facades\Http;

test('bot can tell information from Wikipedia', function () {
    Http::fake([
        config('bot.wikipedia_api_url').'*' => Http::response([
            'query' => [
                'search' => [
                    ['snippet' => 'TEST_SNIPPET'],
                ],
            ],
        ]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('wikipedia');

    $this->assertRequestSent();
})->skip();

test('bot can tell information from Wikipedia (no result)', function () {
    Http::fake([
        config('bot.wikipedia_api_url').'*' => Http::response([
            'query' => [
                'search' => [
                ],
            ],
        ]),
        config('bot.serp_api_url').'*' => Http::response([]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('nayapedia');

    $this->assertRequestSent();
})->skip();
