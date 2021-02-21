<?php

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();
});

test('bot can reply message', function () {
    $this->receiveMessage('Hello bot!');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});
