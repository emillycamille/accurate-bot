<?php

use Illuminate\Support\Facades\Http;

test('bot can open db', function () {
    Http::fake();

    $this->receivePostback('OPEN_DB:1');

    $this->assertRequestSent();
});
