<?php

test('bot can calculate 20 + 21', function () {

    $this->assertReceiveAction(
        '20 + 21',
        'calculateMath',
        [],
        '',
    );
});

