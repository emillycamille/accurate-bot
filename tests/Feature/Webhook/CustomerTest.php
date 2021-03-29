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
        'workPhone' => 'BUSINESS_NUMBER',
        'mobilePhone' => 'MOBILE_NUMBER',
    ],
    [
        'id' => 2,
        'name' => 'CUST_NAME_2',
        'balanceList' => [[
            'balance' => 200000,
        ]],
        'workPhone' => 'BUSINESS_NUMBER',
        'mobilePhone' => 'MOBILE_NUMBER',
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

    test()->assertRequestSent(true);
});

test('bot can handle unknown customer', function () {
    Http::fake();

    $this->receiveMessage('customer');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});

function testFindCustomer(string $mode): void
{
    switch ($mode) {
        case 'multiple':
            $data = CUSTOMERS;
            break;
        case 'single':
            $data = [CUSTOMERS[0]];
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
