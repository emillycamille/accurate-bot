<?php

test('bot can calculate 1+1', function () {
    $this->receiveMessage('1+1');

    $this->assertRequestSent();
});
