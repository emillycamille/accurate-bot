<?php

test('bot can greet user', function () {
    $this->receiveMessage('Halo bot!');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});
