<?php

test('bot can reply message', function () {
    $this->receiveMessage('Hello bot!');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});
