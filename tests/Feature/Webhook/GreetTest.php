<?php

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake([
        config('bot.fb_user_url') . '*' => Http::response([
            'first_name' => 'TEST_FIRST_NAME',
            'last_name' => 'TEST_LAST_NAME',
        ]),
    ]);
});

test('bot can greet user', function () {
    $this->receiveMessage('Halo bot!');

    $this->assertRequestSent();
});
