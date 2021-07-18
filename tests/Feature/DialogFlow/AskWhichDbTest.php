<?php

use Illuminate\Support\Facades\Http;
use App\Models\User;

test('bot can ask which database', function () {
    User::factory()->create();

    Http::fake([
        'db-list.do' => Http::response(['d' => [
            ['id' => 1, 'alias' => 'DB_1'],
            ['id' => 2, 'alias' => 'DB_2'],
        ]]),
    ]);
    
    $this->assertReceiveAction(
        'askWhichDb',
        [],
        'Silakan pilih database',
    );
});

test('bot can show that user has no database', function () {
    User::factory()->create();

    Http::fake([
        'db-list.do' => Http::response(['d' => []]),
    ]);
    
    $this->assertReceiveAction(
        'askWhichDb',
        [],
        'Silakan pilih database',
    );
});

test('bot can open database', function () {
    $user = User::factory()->create();

    Http::fake([
        'open-db.do*' => Http::response([
            'host' => 'DB_HOST',
            'session' => 'DB_SESSION',
        ]),
    ]);

    $this->assertReceiveAction(
        'openDb',
        ['dbId' => 123456],
        'Database berhasil dibuka',
    );

    $this->assertRequestSent();

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'host' => 'DB_HOST',
        'session' => 'DB_SESSION',
        'database_id' => 123456,
    ]);
});
