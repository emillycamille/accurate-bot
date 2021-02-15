<?php

// beforeEach(function () {
//     $userID = "5196920073666570";
// });
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake([
        config('bot.fb_user_url').'*' => Http::response(
            [
        'first_name' => 'TEST_FIRST_NAME',
        ]),
    ]);
});

test('bot can greet user', function () {
    $this->receiveMessage('Halo bot!');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});
