<?php

use Illuminate\Support\Facades\Http;

test('bot can welcome facebook user', function () {
    Http::fake([
        config('bot.fb_api_url').'*' => Http::response([
            'first_name' => 'TEST_FIRST_NAME',
            'last_name' => 'TEST_LAST_NAME',
        ]),
    ]);

    $this->assertReceiveAction(
        'facebookWelcome',
        [],
        'Hai :name! Perkenalkan saya Naya 😊',
    );
});
