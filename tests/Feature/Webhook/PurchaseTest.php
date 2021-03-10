<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

const PURCHASE_ITEMS =
        [
            ['totalAmount' => 2059200000,
            'transDate' => 'TEST_DATE1',
            'statusName' => 'TEST_STATUS1',
            'vendor'=> [
                'name' => 'TEST_NAME1',
            ], ],
            ['totalAmount' => 15999000,
            'transDate' => 'TEST_DATE2',
            'statusName' => 'TEST_STATUS2',
            'vendor'=> [
                'name' => 'TEST_NAME2',
            ], ],
            ['totalAmount' => 215000,
            'transDate' => 'TEST_DATE3',
            'statusName' => 'TEST_STATUS3',
            'vendor'=> [
                'name' => 'TEST_NAME3',
            ], ],
            ['totalAmount' => 35499000,
            'transDate' => 'TEST_DATE4',
            'statusName' => 'TEST_STATUS4',
            'vendor'=> [
                'name' => 'TEST_NAME4',
            ], ],
            ['totalAmount' => 11799000,
            'transDate' => 'TEST_DATE5',
            'statusName' => 'TEST_STATUS5',
            'vendor'=> [
                'name' => 'TEST_NAME5',
            ], ],
            ['totalAmount' => 95000,
            'transDate' => 'TEST_DATE6',
            'statusName' => 'TEST_STATUS6',
            'vendor'=> [
                'name' => 'TEST_NAME6',
            ], ],

        ];

beforeEach(function () {
    User::factory()->withSession()->create();
});

test('bot can show purchase invoice (more than 5 invoices)', function () {
    Http::fake([
        'purchase-invoice/list.do*' => Http::response(['d'=> PURCHASE_ITEMS]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('pembelian sebelumnya');

    $this->assertRequestSent();
});

test('bot can show purchase invoice (less than 5 invoices)', function () {
    Http::fake([
        'purchase-invoice/list.do*' => Http::response(['d'=> array_slice(PURCHASE_ITEMS, 0, 3)]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('purchase masa lalu');

    $this->assertRequestSent();
});
