<?php

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();
});

test('bot can reply message', function () {
    // Turn on "typing on" to assert correct request is sent.
    // Temporarily turned off because new EU rule bans this. 
    config(['bot.typing_on' => false]);

    $this->receiveMessage('Yooooo bot!');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});
