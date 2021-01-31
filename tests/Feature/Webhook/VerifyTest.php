<?php

namespace Tests\Feature\Webhook\Verify;

use OwowAgency\LaravelTestResponse\TestResponse;

test('messenger can verify webhook', function () {
    $query = buildQuery(config('bot.fb_verify_token'));

    $response = makeRequest($query);

    // Assert that the response contains the challenge.
    $response->assertOk()->assertSee('TEST_CHALLENGE');
});

test('messenger cant verify webhook with invalid token', function () {
    // Use invalid token.
    $query = buildQuery('INVALID');

    $response = makeRequest($query);

    // Assert that the verification is not successful.
    $this->assertResponse($response, 422);
});

/**
 * Build query that will be sent by Messenger.
 */
function buildQuery(string $token): string
{
    return http_build_query([
        'hub.mode' => 'subscribe',
        'hub.verify_token' => $token,
        'hub.challenge' => 'TEST_CHALLENGE',
    ]);
}

/**
 * Make request to verify webhook.
 */
function makeRequest(string $query): TestResponse
{
    return test()->getJson("/webhook?$query");
}
