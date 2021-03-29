<?php

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();
});

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

test('bot can calculate "3^2"', function () {
    $this->receiveMessage('3^2');

    $this->assertRequestSent();
});

test('bot can return fallback for invalid math expression', function () {
    $this->receiveMessage('64 /+8');

    $this->assertRequestSent();
});

test('bot can return fallback for division by zero', function () {
    $this->receiveMessage('1/0');

    $this->assertRequestSent();
});

test('bot can return fallback for mismatching parenthesis', function () {
    $this->receiveMessage('(1+2');

    $this->assertRequestSent();
});
