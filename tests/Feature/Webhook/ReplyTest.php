<?php

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();
});

test('bot can reply message', function () {
    // Turn on "typing on" to assert correct request is sent.
    config(['bot.typing_on' => true]);

    $this->receiveMessage('Yooooo bot!');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});
