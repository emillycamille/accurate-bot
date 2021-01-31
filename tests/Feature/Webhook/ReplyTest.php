<?php

namespace Tests\Feature\Webhook\Reply;

test('bot can reply message', function () {
    $response = $this->receiveMessage('Hello bot!');
});
