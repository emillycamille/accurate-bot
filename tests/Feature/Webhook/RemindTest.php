<?php

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();
});

test('bot can explode remind message', function () {
    $this->receiveMessage('remind makan 20:00');

    $this->assertRequestSent();
})->skip();
