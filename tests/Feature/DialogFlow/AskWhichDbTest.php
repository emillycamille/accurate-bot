<?php

test('bot can ask which database', function () {
    $this->assertReceiveAction(
        'askWhichDb',
        [],
        'Silakan pilih database',
    );
});
