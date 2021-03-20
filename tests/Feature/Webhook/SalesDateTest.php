<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

const SALES_DATE_ITEMS =
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

test('bot can show sales invoice at selected date', function () {
    Http::fake([
        'sales-invoice/list.do*' => Http::response(SALES_DATE_ITEMS),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('penjualan 3/7/2021');

    $this->assertRequestSent();
});

test('bot can\'t show sales invoice at selected date', function () {
    Http::fake([
        'sales-invoice/list.do*' => Http::response(['d' => []]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('penjualan 3/7/2021');

    $this->assertRequestSent();
});
