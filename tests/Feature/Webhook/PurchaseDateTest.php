<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

const PURCHASE_DATE_ITEMS = [
    's' => true,
    'd' => [
        ['totalAmount' => 1],
        ['totalAmount' => 1],
        ['totalAmount' => 1],
        ['totalAmount' => 1],
        ['totalAmount' => 1],
    ],
    'sp' => ['page' => 1, 'pageCount' => 1],
];

beforeEach(function () {
    User::factory()->withSession()->create();
});

test('bot can show purchase invoice at selected date', function () {
    Http::fake([
        'purchase-invoice/list.do*' => Http::response(PURCHASE_DATE_ITEMS),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('pembelian 3/7/2021 adalah');

    $this->assertRequestSent();
});

test('bot can\'t show purchase invoice at selected date', function () {
    Http::fake([
        'purchase-invoice/list.do*' => Http::response(['d' => []]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('pembelian 3/7/2021');

    $this->assertRequestSent();
});
