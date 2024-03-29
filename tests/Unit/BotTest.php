<?php

use App\Bot\Bot;
use App\Models\User;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake([
        'EXPIRED/SESSION' => Http::sequence()
            ->push([
                's' => false,
                'd' => 'Session key tidak tepat',
            ], 401)
            ->push(['s' => true]),

        'INVALID/TOKEN' => Http::response([
            'error' => 'invalid_token',
        ], 401),

        'db-list.do' => Http::response([
            // CHANGE: remove, pake data_get di canConnectAccurate.
            's' => true,
            'd' => [
                ['id' => 1, 'alias' => 'ALIAS_1'],
            ],
        ]),

        'open-db.do*' => Http::response([
            'host' => 'NEW_HOST',
            'session' => 'NEW_SESSION',
        ]),

        '*' => Http::response(['s' => true]),
    ]);
});

it('sends login button if psid is unrecognized', function () {
    Bot::askAccurate('PS_ID', 'ANY_URL');

    $this->assertRequestSent();
})->skip();

it('sends login button if access token is invalid', function () {
    User::factory()->withSession()->create();

    Bot::askAccurate('PS_ID', 'INVALID/TOKEN');

    $this->assertRequestSent();
})->skip();

it('asks accurate if user has session', function () {
    User::factory()->withSession()->create();

    Bot::askAccurate('PS_ID', 'DOMAIN/URL');

    $this->assertRequestSent();
});

it('asks which db if user doesnt have session', function () {
    User::factory()->create();

    Bot::askAccurate('PS_ID', 'DOMAIN/URL');

    $this->assertRequestSent();
});

it('asks basic accurate even if user doesnt have session', function () {
    User::factory()->create();

    Bot::askAccurate('PS_ID', 'BASIC_URL');

    $this->assertRequestSent();
});

it('refreshes session if user session expired', function () {
    User::factory()->withSession()->create();

    Bot::askAccurate('PS_ID', 'EXPIRED/SESSION');

    $this->assertRequestSent(true);
});
