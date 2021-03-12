<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
});

test('bot can greet user', function () {
    $user = User::factory()->create();

    $data = [
        'fb_firstname' => 'TEST_FIRST_NAME',
        'fb_lastname' => 'TEST_LAST_NAME',
    ];

    Http::fake([
        config('bot.fb_user_url').'*' => Http::response([
            'first_name' => $data['fb_firstname'],
            'last_name' => $data['fb_lastname'],
        ]),
    ]);

    $this->receiveMessage('Halo bot!');

    $this->assertRequestSent();

    $this->assertDatabaseHas('users', $data + [
        'fb_firstname' => $user->fb_firstname,
    ]);
});
