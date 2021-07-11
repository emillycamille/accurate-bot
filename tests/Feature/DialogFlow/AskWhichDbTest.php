<?php

test('bot can ask which database', function () {
    $this->assertReceiveAction(
        'askWhichDb',
        [],
        'Silakan pilih database',
    );
});

test('bot can open database', function () {
    $this->assertReceiveAction(
        'openDb',
        ['dbId' => 123456],
        'Database berhasil dibuka',
    );
});
