<?php

use Illuminate\Support\Facades\Http;

test('bot can show help', function () {
    Http::fake();
    $this->receiveMessage('help');

    $this->assertRequestSent();
});
