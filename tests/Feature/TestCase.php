<?php

namespace Tests\Feature;

use OwowAgency\LaravelTestResponse\TestResponse;
use Tests\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

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
     * Make request from Messenger that simulates the bot receiving $message.
     */
    public function receiveMessage(string $message): TestResponse
    {
        // The data sent by Messenger.
        // https://developers.facebook.com/docs/messenger-platform/reference/webhook-events/messages
        $data = [
            'object' => 'page',
            'entry' => [
                [
                    'id' => 'PAGE_ID',
                    'time' => 1458692752478,
                    'messaging' => [
                        [
                            'sender' => ['id' => 'PS_ID'],
                            'recipient' => ['id' => 'PAGE_ID'],
                            'message' => [
                                'text' => $message,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        Http::fake([
            // Stub a JSON response for Website endpoints...
            'http://api.openweathermap.org/*' => Http::response(['weather' => 'clear'], 200, ['Headers']),
        ]);

        $response = $this->postJson('/webhook', $data);

        // Messenger expects response 200 to be returned within 20 seconds.
        return $response->assertStatus(200);
    }
}
