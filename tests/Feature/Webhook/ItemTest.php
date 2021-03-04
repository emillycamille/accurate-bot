<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

test('bot can list items', function () {
    User::factory()->withSession()->create();

    Http::fake([
        'item/list.do*' => Http::response(['d' => [
            [
                'name' => 'ITEM_1',
                'unitPrice' => 50000,
                'availableToSell' => 100,
            ],
            [
                'name' => 'ITEM_2',
                'unitPrice' => 20000,
                'availableToSell' => 200,
            ],
        ]]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('list item');

    $this->assertRequestSent(true);
});
