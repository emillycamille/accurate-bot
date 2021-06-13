<?php

namespace Tests\Feature;

use OwowAgency\LaravelTestResponse\TestResponse;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Assert response status code and snapshot its content.
     */
    public function assertResponse(TestResponse $response, int $status = 200)
    {
        $response->assertStatus($status);

        $this->assertMatchesJsonSnapshot($response->content());
    }

    /**
     * Make request from Messenger that simulates the bot receiving $text.
     */
    public function receiveMessage(string $text): TestResponse
    {
        // https://developers.facebook.com/docs/messenger-platform/reference/webhook-events/messages
        $data = ['message' => compact('text')];

        return $this->receiveEvent($data);
    }

    /**
     * Make request from Messenger that simulates the bot receiving postback with $payload.
     */
    public function receivePostback(string $payload): TestResponse
    {
        // https://developers.facebook.com/docs/messenger-platform/reference/webhook-events/messaging_postbacks
        $data = ['postback' => compact('payload')];

        return $this->receiveEvent($data);
    }

    /**
     * Make request from Messenger that simulates the bot receiving a messaging event.
     */
    public function receiveEvent(array $data): TestResponse
    {
        $payload = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => 'PAGE_ID',
                    'time' => 1458692752478,
                    'messaging' => [
                        [
                            'sender' => ['id' => 'PS_ID', 'user_ref' => 'PS_ID'],
                            'recipient' => ['id' => 'PAGE_ID'],
                        ] + $data,
                    ],
                ],
            ],
        ];

        $response = $this->postJson('/webhook', $payload);

        // Messenger expects response 200 to be returned within 20 seconds.
        return $response->assertStatus(200);
    }

    public function assertReceiveAction(string $queryText, string $action, array $params, string $template): TestResponse
    {
        $payload = [
            'queryResult' => [
                'queryText' => $queryText,
                'action' => $action,
                'parameters' => $params,
                'fulfillmentMessages' => [
                    [
                        'text' => [
                            'text' => [
                                $template,
                            ],
                        ],
                        'platform' => 'FACEBOOK',
                    ],
                ],
            ],
        ];

        $response = $this->postJson('/dialog-flow', $payload);

        $response->assertStatus(200);

        $this->assertMatchesJsonSnapshot($response->getContent());

        return $response;
    }
}
