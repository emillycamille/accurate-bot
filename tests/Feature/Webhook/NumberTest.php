<?php

use Illuminate\Support\Facades\Http;

test('bot can greet user', function () {
    Http::fake();

    $this->receiveMessage('1');

    $this->assertRequestSent();
});
