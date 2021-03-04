<?php

use App\Bot\Bot;
use Illuminate\Support\Facades\Http;

it('sends login button if psid is unrecognized', function () {
    Http::fake();

    Bot::askAccurate('PS_ID', 'ANY_URL');

    $this->assertRequestSent();
});
