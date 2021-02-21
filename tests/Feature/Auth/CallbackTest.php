<?php

use Illuminate\Support\Facades\Http;

test('user can store accurate access token', function () {
    $data = [
        'name' => 'USER_NAME',
        'email' => 'USER_EMAIL',
        'access_token' => 'ACCESS_TOKEN',
        'refresh_token' => 'REFRESH_TOKEN',
    ];

    Http::fake([
        config('accurate.auth_token_url') => Http::response([
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'user' => [
                'name' => $data['name'],
                'email' => $data['email'],
            ],
        ]),
    ]);

    $this->get('auth/callback?'.http_build_query([
        'code' => 'ACCURATE_CODE',
    ]));

    $this->assertRequestSent(true);

    $this->assertDatabaseHas('users', $data);
});
