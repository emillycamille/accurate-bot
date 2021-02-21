<?php

use Illuminate\Support\Facades\Http;

test('bot can send login button', function () {
    Http::fake();

    $this->receiveMessage('Login');

    $this->assertRequestSent();
});
