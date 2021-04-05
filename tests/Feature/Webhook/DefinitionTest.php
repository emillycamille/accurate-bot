<?php

use Illuminate\Support\Facades\Http;

test('bot can tell definition', function () {
    Http::fake([
        config('bot.definition_api_url').'*' => Http::response([
            'kateglo' => ['definition' => [
                ['def_text' => 'DEFINITION_TEST'],
            ]],
        ]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('arti duduk');

    $this->assertRequestSent();
});
