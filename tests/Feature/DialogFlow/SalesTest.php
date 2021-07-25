<?php
namespace Tests\Feature\DialogFlow\Sales;

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

test('bot can get today\'s sales', function () {
    User::factory()->withSession()->create();

    Http::fake([
        'sales-invoice/list.do*' => Http::response(SALES_ITEMS),
    ]);

    $this->assertReceiveAction(
        'getSales',
        ['time' => '2021-07-25T12:00:00+07:00'],
        'Total penjualan :time adalah :amount',
    );
});
