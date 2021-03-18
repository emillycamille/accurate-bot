<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

test('bot can respond to Get Started', function () {
    $data = [
        'fb_firstname' => 'TEST_FIRST_NAME',
        'fb_lastname' => 'TEST_LAST_NAME',
    ];

    Http::fake([
        config('bot.fb_api_url') . '*' => Http::response([
            'first_name' => $data['fb_firstname'],
            'last_name' => $data['fb_lastname'],
        ]),
    ]);

    $this->receivePostback('FACEBOOK_WELCOME');

    $this->assertRequestSent();
});
