<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

test('bot can open db', function () {
    $user = User::factory()->create();

    $data = ['s' => true,
        'host' => 'DB_HOST',
        'session' => 'DB_SESSION',
    ];

    Http::fake([
        config('accurate.api_url').'open-db.do*' => Http::response($data),
        '*' => Http::response([
            's' => true,]),
    ]);

    $this->receivePostback('OPEN_DB:PS_ID:1');

    $this->assertRequestSent();

    $this->assertDatabaseHas('users', $data + [
        'id' => $user->id,
    ]);
});
