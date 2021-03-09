<?php

test('bot can show help', function () {
    $this->receiveMessage('help');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});
