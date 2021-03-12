<?php

use Illuminate\Support\Facades\Http;
use App\Models\User;

beforeEach(function () {
});

test('bot can greet user', function () {
    $user = User::factory()->create();

    $data = [
        'fb_firstname' => 'TEST_FIRST_NAME',
    ];

    Http::fake([
        config('bot.fb_user_url') . '*' => Http::response([
            'first_name' => 'TEST_FIRST_NAME',
            'last_name' => 'TEST_LAST_NAME',
        ]),
    ]);

    $this->receiveMessage('Halo bot!');

    $this->assertRequestSent();

    $this->assertDatabaseHas('users', $data + [
        'fb_firstname' => $user->fb_firstname,
    ]);
});
