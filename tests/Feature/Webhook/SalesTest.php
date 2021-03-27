<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

const SALES_ITEMS =
[
    's' => true,
    'd' => [
        [
            'totalAmount' => 1,
            'transDate' => 'TEST_DATE1',
            'statusName' => 'TEST_STATUS1',
            'customer' => [
                'name' => 'TEST_NAME1',
            ],
        ],
        [
            'totalAmount' => 1,
            'transDate' => 'TEST_DATE2',
            'statusName' => 'TEST_STATUS2',
            'customer' => [
                'name' => 'TEST_NAME2',
            ],
        ],
        [
            'totalAmount' => 1,
            'transDate' => 'TEST_DATE3',
            'statusName' => 'TEST_STATUS3',
            'customer' => [
                'name' => 'TEST_NAME3',
            ],
        ],
        [
            'totalAmount' => 1,
            'transDate' => 'TEST_DATE4',
            'statusName' => 'TEST_STATUS4',
            'customer' => [
                'name' => 'TEST_NAME4',
            ],
        ],
        [
            'totalAmount' => 1,
            'transDate' => 'TEST_DATE5',
            'statusName' => 'TEST_STATUS5',
            'customer' => [
                'name' => 'TEST_NAME5',
            ],
        ],

    ],
    'sp' => [
        'page' => 1,
        'pageCount' => 2,
        'pageSize' => 5,
    ],

];

beforeEach(function () {
    User::factory()->withSession()->create();
});

test('bot can show sales invoice at that day', function () {
    Http::fake([
        'sales-invoice/list.do*' => Http::response(SALES_ITEMS),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('penjualan');

    $this->assertRequestSent();
});

test('bot can show sales invoice (more than 5 invoices)', function () {
    Http::fake([
        'sales-invoice/list.do*' => Http::response(SALES_ITEMS),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('histori penjualan sebelumnya');

    $this->assertRequestSent();
});

test('bot can show sales invoice (less than 5 invoices)', function () {
    Http::fake([
        'sales-invoice/list.do*' => Http::response(
            [
                's' => true,
                'd' => array_slice(SALES_ITEMS['d'], 0, 3),
                'sp' => SALES_ITEMS['sp'],
            ]
        ),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('history sales masa lalu');

    $this->assertRequestSent();
});

test('bot can show sales invoice at selected date', function () {
    Http::fake([
        'sales-invoice/list.do*' => Http::response(
            [
                's' => true,
                'd' => SALES_ITEMS['d'],
                'sp' => ['pageCount' => 1],
            ]
        ),
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
