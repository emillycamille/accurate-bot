<?php

use Illuminate\Support\Facades\Http;
use App\Models\User;

beforeEach(function () {
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

    $detailResponse = [
        [
            'transDateView' => '17/8/2019',
            'detailItem' => [
                [
                    'detailName' => 'TestName1',
                    'quantity' => '100',
                    'salesAmount' => '17599000',
                ],
                [
                    'detailName' => 'TestName1',
                    'quantity' => '100',
                    'salesAmount' => '17599000',
                ],
                [
                    'detailName' => 'TestName1',
                    'quantity' => '100',
                    'salesAmount' => '17599000',
                ],
                [
                    'detailName' => 'TestName1',
                    'quantity' => '100',
                    'salesAmount' => '17599000',
                ],
                [
                    'detailName' => 'TestName1',
                    'quantity' => '100',
                    'salesAmount' => '17599000',
                ],
            ],
        ],
    ];

    Http::fake([
        'sales-invoice/list.do*' => Http::response(['d' => $listResponse]),
        'sales-invoice/detail.do*' => Http::response(['d' => $detailResponse]),
        '*' => Http::response(),
    ]);
});

test('bot can show sales invoice', function () {
    User::factory()->withSession()->create();
    
    $this->receiveMessage('penjualan');

    $this->assertRequestSent();
});
