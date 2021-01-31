<?php

test('messenger can verify webhook', function () {
    // Simulate query that will be sent by Messenger.
    $query = http_build_query([
        'hub' => [
            'mode' => 'subscribe',
            'verify_token' => config('bot.fb_verify_token'),
            'challenge' => 'TEST_CHALLENGE',
        ],
    ]);

    // Make request to verify webhook.
    $response = $this->getJson("/webhook?$query");

    // Assert that the response contains the challenge.
    $response->assertOk()->assertSee('TEST_CHALLENGE');
});

test('messenger cant verify webhook with invalid token', function () {
    $query = http_build_query([
        'hub' => [
            'mode' => 'subscribe',
            // Supply invalid token.
            'verify_token' => 'INVALID',
            'challenge' => 'TEST_CHALLENGE',
        ],
    ]);

    $response = $this->getJson("/webhook?$query");

    // Assert that the verification is not successful.
    $response->assertStatus(422);
});
