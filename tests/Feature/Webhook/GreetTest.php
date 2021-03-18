<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

test('bot can greet user', function () {
    Http::fake();
    $user = User::factory()->withFbName()->create();

    // $data = [
    //     'fb_firstname' => 'TEST_FIRST_NAME',
    //     'fb_lastname' => 'TEST_LAST_NAME',
    // ];

    $this->receiveMessage('Halo bot!');

    $this->assertRequestSent();

    // $this->assertDatabaseHas('users', $data + [
    //     'fb_firstname' => $user->fb_firstname,
    // ]);
});
