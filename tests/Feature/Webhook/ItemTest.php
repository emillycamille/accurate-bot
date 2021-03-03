<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

test('bot can list items', function () {
    User::factory()->create();

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
    ]);

    $this->receiveMessage('List barang');

    $this->assertRequestSent(true);
});
