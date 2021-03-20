<?php

use Illuminate\Support\Facades\Http;

test('bot can respond to Get Started', function () {

    Http::fake([
        config('bot.fb_api_url') . '*' => Http::response([
            'first_name' => 'TEST_FIRST_NAME',
            'last_name' => 'TEST_LAST_NAME',
        ]),
    ]);

    $this->receivePostback('FACEBOOK_WELCOME');

    $this->assertRequestSent();
});
