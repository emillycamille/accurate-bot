<?php

use Illuminate\Support\Facades\Http;

test('bot can send login button', function () {
    Http::fake();

    $this->assertReceiveAction(
        'login',
        [],
        'Silakan login dengan akun accurate mu',
    );

    $this->assertRequestSent();
});
