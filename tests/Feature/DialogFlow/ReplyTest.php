<?php

test('bot can reply message', function () {
    $payload = [
        'queryResult' => [
            'queryText' => 'Halo Naya',
        ],
    ];

    $response = $this->postJson('/dialog-flow', $payload);

    $response->assertStatus(200);

    $this->assertMatchesJsonSnapshot($response->getContent());
});
