<?php

use Illuminate\Support\Facades\Http;

test('user can store accurate access token', function () {
    Http::fake();

    $this->get('auth/callback?'.http_build_query([
        'code' => 'ACCURATE_CODE',
    ]));

    $this->assertRequestSent(true);
});
