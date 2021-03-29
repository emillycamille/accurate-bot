<?php

namespace Tests\Feature\Webhook\Customer;

use App\Models\User;
use Illuminate\Support\Facades\Http;

const CUSTOMERS = [
    [
        'id' => 1,
        'name' => 'CUST_NAME_1',
        'balanceList' => [[
            'balance' => 250000,
        ]],
        'workPhone' => 'WORK_PHONE',
        'mobilePhone' => '',
    ],
    [
        'id' => 2,
        'name' => 'CUST_NAME_2',
        'balanceList' => [[
            'balance' => 200000,
        ]],
        'workPhone' => 'WORK_PHONE',
        'mobilePhone' => 'MOBILE_PHONE',
    ],
];

beforeEach(function () {
    User::factory()->withSession()->create();
});

test('bot can show multiple possible customers', function () {
    testFindCustomer('multiple');
});

test('bot can show single customer detail', function () {
    testFindCustomer('single');
});

test('bot can show no customers found', function () {
    testFindCustomer('none');
});

test('bot can detail customer', function () {
    Http::fake([
        'customer/detail.do*' => Http::response(['s' => true, 'd' => CUSTOMERS[0]]),
        '*' => Http::response(),
    ]);

    $this->receivePostback('DETAIL_CUSTOMER:PS_ID:1');

    $this->assertRequestSent(true);
});

test('bot can handle unknown customer', function () {
    Http::fake();

    $this->receiveMessage('customer');

    $this->assertRequestSent();
});

function testFindCustomer(string $mode): void
{
    switch ($mode) {
        case 'multiple':
            $data = CUSTOMERS;
            break;
        case 'single':
            $data = [CUSTOMERS[1]];
            break;
        case 'none':
            $data = [];
            break;
    }

    Http::fake([
        'customer/list.do*' => Http::response(['s' => true, 'd' => $data]),
        '*' => Http::response(),
    ]);

    test()->receiveMessage('customer KEYWORD');

    test()->assertRequestSent(true);
}
