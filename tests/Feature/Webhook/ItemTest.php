<?php

namespace Tests\Feature\Webhook\Item;

use App\Models\User;
use Illuminate\Support\Facades\Http;

const ITEMS = [
    [
        'id' => 1,
        'name' => 'KEYWORD_1',
        'unitPrice' => 50000,
        'availableToSell' => 100,
        'detailItemImage' => [[
            'fileName' => 'ITEM_IMAGE',
        ]],
    ],
    [
        'id' => 2,
        'name' => 'KEYWORD_2',
        'unitPrice' => 20000,
        'availableToSell' => 200,
    ],
];

beforeEach(function () {
    User::factory()->withSession()->create();
});

test('bot can show multiple possible items', function () {
    testFindItem('multiple');
});

test('bot can show single item detail', function () {
    testFindItem('single');
});

test('bot can show no items found', function () {
    testFindItem('none');
});

test('bot can detail item', function () {
    Http::fake([
        'item/detail.do*' => Http::response(['s' => true, 'd' => ITEMS[0]]),
        '*' => Http::response(),
    ]);

    $this->receivePostback('DETAIL_ITEM:PS_ID:1');

    test()->assertRequestSent(true);
});

test('bot can show item image', function () {
    Http::fake();

    $this->receivePostback('SHOW_IMAGE:PS_ID:/ITEM_IMAGE');

    test()->assertRequestSent(true);
});

test('bot can handle unknown item', function () {
    Http::fake();

    $this->receiveMessage('item');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});

function testFindItem(string $mode): void
{
    $message = 'item';

    switch ($mode) {
        case 'multiple':
            $data = ITEMS;
            break;
        case 'single':
            $data = [ITEMS[0]];
            // Test that 'stok' should trigger the same function as 'item'.
            $message = 'stok';
            break;
        case 'none':
            $data = [];
            break;
    }

    Http::fake([
        'item/list.do*' => Http::response(['s' => true, 'd' => $data]),
        '*' => Http::response(),
    ]);

    test()->receiveMessage("$message KEYWORD");

    test()->assertRequestSent(true);
}
