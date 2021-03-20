<?php

namespace Tests\Feature\Webhook\Customer;

use App\Models\User;
use Illuminate\Support\Facades\Http;

const CUSTOMERS = [
    [
        'id' => 1,
        'name' => 'KEYWORD_1',
        'balanceList' => [[
            'balance' => 250000,
        ]],
        'createDate' => '19/04/2018 14:29:29',
    ],
    [
        'id' => 2,
        'name' => 'KEYWORD_2',
        'balanceList' => [[
            'balance' => 200000,
        ]],
        'createDate' => '21/04/2018 20:00:29',
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

function testFindCustomer(string $mode): void
{
    switch ($mode) {
        case 'multiple':
            $data = CUSTOMERS; break;
        case 'single':
            $data = [CUSTOMERS[0]]; break;
        case 'none':
            $data = []; break;
    }

    Http::fake([
        'customer/list.do*' => Http::response(['s' => true, 'd' => $data]),
        '*' => Http::response(),
    ]);

    test()->receiveMessage('customer KEYWORD');

    test()->assertRequestSent(true);
}
