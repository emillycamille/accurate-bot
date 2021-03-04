<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

test('bot can show last 5 purchase invoices', function () {
    $user = User::factory()->create();

    $data = [
        'host' => 'DB_HOST',
        'session' => 'DB_SESSION',
    ];

    Http::fake([
        config('accurate.api_url').'open-db.do*' => Http::response($data),
        '*' => Http::response(),
    ]);

    $this->receivePostback('OPEN_DB:PS_ID:1');

    $this->assertRequestSent();

    $this->assertDatabaseHas('users', $data + [
        'id' => $user->id,
    ]);
});

function testPurchaseInvoice(): void
{
    User::factory()->withSession()->create();

    $data = [
        [
            'id' => 1,
        ],
        [
            'id' => 2,
        ],
        [
            'id' => 3,
        ],
    ];

    Http::fake([
        'purchase-invoice/list.do*' => Http::response(['d' => $data]),
    ]);
}
