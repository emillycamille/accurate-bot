<?php

test('bot can calculate "1+1"', function () {
    $this->receiveMessage('1+1');

    $this->assertRequestSent();
});

test('bot can calculate "272  -  53"', function () {
    $this->receiveMessage('272  -  53');

    $this->assertRequestSent();
});

test('bot can calculate "4 * 25"', function () {
    $this->receiveMessage(' 4 * 25');

    $this->assertRequestSent();
});

test('bot can calculate "64 /8"', function () {
    $this->receiveMessage('64 /8');

    $this->assertRequestSent();
});

test('bot can calculate "1 + 16 / 8"', function () {
    $this->receiveMessage('1 + 16 / 8');

    $this->assertRequestSent();
});

test('bot cant calculate "64 /+8"', function () {
    $this->receiveMessage('64 /+8');

    $this->assertRequestSent();
});

test('bot cant calculate "1/0"', function () {
    $this->receiveMessage('1/0');

    $this->assertRequestSent();
});

test('bot cant calculate "(1+2"', function () {
    $this->receiveMessage('(1+2');

    $this->assertRequestSent();
});
