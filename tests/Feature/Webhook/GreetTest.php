<?php

// beforeEach(function () {
//     $userID = "5196920073666570";
// });
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();
});

test('bot can greet user', function () {
    $this->receiveMessage('Halo bot!');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});
