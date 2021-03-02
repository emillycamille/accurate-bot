<?php

use Illuminate\Support\Facades\Http;

test('user can store accurate access token', function () {
    $data = [
        'psid' => 'PS_ID',
        'name' => 'USER_NAME',
        'email' => 'USER_EMAIL',
        'access_token' => 'ACCESS_TOKEN',
        'refresh_token' => 'REFRESH_TOKEN',
    ];

    Http::fake([
        config('accurate.access_token_url') => Http::response([
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'user' => [
                'name' => $data['name'],
                'email' => $data['email'],
            ],
        ]),
        config('accurate.api_url').'*' => Http::response(['d' => [
            ['id' => 1, 'alias' => 'ALIAS_1'],
            ['id' => 2, 'alias' => 'ALIAS_2'],
        ]]),
    ]);

    $this->get('auth/callback?'.http_build_query([
        'code' => 'ACCURATE_CODE',
        'psid' => $data['psid'],
    ]));

    $this->assertRequestSent(true);

    $this->assertDatabaseHas('users', $data);
});
