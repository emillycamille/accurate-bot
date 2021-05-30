<?php

use Illuminate\Support\Facades\Http;

test('bot can reply message', function () {
  Http::fake([
      config('bot.weather_api_url').'*' => Http::response([
          'cod' => 200,
          'weather' => [['description' => 'TEST_WEATHER_RESULT']],
          'name' => 'CITY_NAME',
          'main' => ['temp' => 'TEMPERATURE'],
      ]),
  ]);
  
    $payload = [
        'queryResult' => [
            'action' => 'getWeather',
            'parameters' => [
                'city' => 'Eindhoven',
            ],
        ],
        'fulfillmentMessages' => [
            [
              'text' => [
                'text' => [
                  'Sekarang di Eindhoven lagi :description dengan temperatur :temperature derajat',
                ],
              ],
              'platform' => 'FACEBOOK',
            ],
          ],
    ];

    $response = $this->postJson('/dialog-flow', $payload);

    $response->assertStatus(200);

    $this->assertMatchesJsonSnapshot($response->getContent());
});
