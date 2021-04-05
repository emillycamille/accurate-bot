<?php

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake([
        config('bot.translate_api_url').'*' => Http::response([
            'status' => true,
            'message' => 'success',
            'data' => ['result' => 'TRANSLATED_TEXT'],
        ]),
        '*' => Http::response(),
    ]);
});

test('bot can translate word to English', function () {
    $this->receiveMessage('translate sekolah');

    $this->assertRequestSent();
});

test('bot can translate sentence to English', function () {
    $this->receiveMessage('terjemahkan sekolah saya ada di Surabaya');

    $this->assertRequestSent();
});

test('bot can handle unknown translate text', function () {
    $this->receiveMessage('Terjemahkan');

    $this->assertRequestSent();
});
