<?php

test('bot can tell weather at Jakarta', function () {
    $this->receiveMessage('    cuaca   sekarang di kota  jakarta   ');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});

test('bot can tell weather at Denpasar', function () {
    $this->receiveMessage('    cuaca  saat ini di  Denpasar   ');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});

test('bot can tell weather at Medan', function () {
    $this->receiveMessage('    cuaca   di kota  Medan   ');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});

test('bot can tell weather at Bandung', function () {
    $this->receiveMessage('Cuaca di Bandung');

    // Assert that correct Send API request is sent.
    $this->assertRequestSent();
});
