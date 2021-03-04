<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

test('bot can show multiple possible items', function () {
    testFindItem('multiple');
});

test('bot can show single item detail', function () {
    testFindItem('single');
});

test('bot can show no items found', function () {
    testFindItem('none');
});

function testFindItem(string $mode): void
{
    User::factory()->withSession()->create();

    $items = [
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
    ];

    switch ($mode) {
        case 'multiple':
            $data = $items; break;
        case 'single':
            $data = [$items[0]]; break;
        case 'none':
            $data = []; break;
    }

    Http::fake([
        'item/list.do*' => Http::response(['d' => $data]),
        '*' => Http::response(),
    ]);

    test()->receiveMessage('item KEYWORD');

    test()->assertRequestSent(true);
}
