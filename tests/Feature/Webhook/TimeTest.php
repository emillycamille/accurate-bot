<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();

    $time = new Carbon('06-02-2021 10:00');

    $this->travelTo($time);
});

test('bot can tell time', function () {
    $this->receiveMessage('jam');

    $this->assertRequestSent();
});

test('bot can tell day', function () {
    $this->receiveMessage('hari');

    $this->assertRequestSent();
});

test('bot can tell day and time', function () {
    $this->receiveMessage('hari jam');

    $this->assertRequestSent();
});
