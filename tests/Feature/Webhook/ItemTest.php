<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    User::factory()->withSession()->create();
});

test('bot can show possible items', function () {
    Http::fake([
        'item/list.do*' => Http::response(['d' => [
            [
                'id' => 1,
                'name' => 'KEYWORD_1',
                'unitPrice' => 50000,
                'availableToSell' => 100,
            ],
            [
                'id' => 2,
                'name' => 'KEYWORD_2',
                'unitPrice' => 20000,
                'availableToSell' => 200,
            ],
        ]]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('item KEYWORD');

    $this->assertRequestSent(true);
});
