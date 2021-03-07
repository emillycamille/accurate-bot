<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    User::factory()->withSession()->create();

    $listResponse = [
        [
            'id' => 1,
        ],
        [
            'id' => 2,
        ],
        [
            'id' => 3,
        ],
        [
            'id' => 4,
        ],
        [
            'id' => 5,
        ],
    ];
    /* $date = $items['d']['transDateView'];
        $name = $items['d']['detailItem'][0]['detailName'];
        $quantity = $items['d']['detailItem'][0]['quantity'];
        $price = $items['d']['detailItem'][0]['itemCost']; */

    $detailResponse = [
        [
            'transDateView' => '17/8/2019',
            'detailItem' => [
                [
                    'detailName' => 'TestName1',
                    'quantity' => '100',
                    'itemCost' => '17599000',
                ],
            ],
        ],
    ];

    Http::fake([
        'purchase-invoice/list.do*' => Http::response(['d' => $listResponse]),
    ]);
    Http::fake([
        'purchase-invoice/detail.do*' => Http::response(['d' => $detailResponse]),
    ]);
});

test('bot can show sales invoice', function () {
    test()->receiveMessage('pembelian');

    test()->assertRequestSent();
});
