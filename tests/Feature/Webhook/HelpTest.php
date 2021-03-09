<?php
use Illuminate\Support\Facades\Http;

test('bot can show help', function () {
    Http::fake();
    $this->receiveMessage('help');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});
