<?php

test('bot can send login button', function () {
    $this->receiveMessage('Login');

    $this->assertRequestSent();
});
