<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

const PURCHASE_ITEMS =
[
    's' => true,
    'd' => [
        [
            'totalAmount' => 1,
            'transDate' => 'TEST_DATE1',
            'statusName' => 'TEST_STATUS1',
            'vendor' => [
                'name' => 'TEST_NAME1',
            ],
        ],
        [
            'totalAmount' => 1,
            'transDate' => 'TEST_DATE2',
            'statusName' => 'TEST_STATUS2',
            'vendor' => [
                'name' => 'TEST_NAME2',
            ],
        ],
        [
            'totalAmount' => 1,
            'transDate' => 'TEST_DATE3',
            'statusName' => 'TEST_STATUS3',
            'vendor' => [
                'name' => 'TEST_NAME3',
            ],
        ],
        [
            'totalAmount' => 1,
            'transDate' => 'TEST_DATE4',
            'statusName' => 'TEST_STATUS4',
            'vendor' => [
                'name' => 'TEST_NAME4',
            ],
        ],
        [
            'totalAmount' => 1,
            'transDate' => 'TEST_DATE5',
            'statusName' => 'TEST_STATUS5',
            'vendor' => [
                'name' => 'TEST_NAME5',
            ],
        ],

    ],
    'sp' => [
        'page' => 1,
        'pageCount' => 12,
        'pageSize' => 5,
    ],

];

beforeEach(function () {
    User::factory()->withSession()->create();
});

test('bot can show purchase invoice (more than 5 invoices)', function () {
    Http::fake([
        'purchase-invoice/list.do*' => Http::response(PURCHASE_ITEMS),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('pembelian sebelumnya');

    $this->assertRequestSent();
});

test('bot can show purchase invoice (less than 5 invoices)', function () {
    Http::fake([
        'purchase-invoice/list.do*' => Http::response(
            [
                's' => true,
                'd' => array_slice(PURCHASE_ITEMS['d'], 0, 3),
                'sp' => PURCHASE_ITEMS['sp'],
            ]
        ),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('purchase masa lalu');

    $this->assertRequestSent();
});

test('bot can show purchase invoice at selected date', function () {
    Http::fake([
        'purchase-invoice/list.do*' => Http::response(
            [
                's' => true,
                'd' => PURCHASE_ITEMS['d'],
                'sp' => ['pageCount' => 1],
            ]
        ),
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
