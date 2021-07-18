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
})->only();

test('bot can open database', function () {
    $this->assertReceiveAction(
        'openDb',
        ['dbId' => 123456],
        'Database berhasil dibuka',
    );
});
