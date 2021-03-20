<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

const PURCHASE_ITEMS =
    ['s' => true,
    'd' => [
            ['totalAmount' => 2059200000],
            ['totalAmount' => 15999000],
            ['totalAmount' => 215000],
            ['totalAmount' => 35499000],
            ['totalAmount' => 11799000],
        ],
        'sp' => [
            'page' => 1,
        'pageCount' => 1,
        ],

    ];

beforeEach(function () {
    User::factory()->withSession()->create();
});

test('bot can show purchase invoice at selected date', function () {
    Http::fake([
        'purchase-invoice/list.do*' => Http::response(PURCHASE_ITEMS),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('pembelian 3/7/2021');

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