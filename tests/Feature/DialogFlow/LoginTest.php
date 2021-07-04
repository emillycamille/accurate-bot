<?php

test('bot can send login button', function () {
    $this->assertReceiveAction(
        'login',
        [],
        'Silakan login dengan akun accurate mu',
    );
});
