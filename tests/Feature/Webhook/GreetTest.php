<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

test('bot can greet user', function () {
    Http::fake();

    User::factory()->create();

    $this->receiveMessage('Halo bot!');

    $this->assertRequestSent();
});
