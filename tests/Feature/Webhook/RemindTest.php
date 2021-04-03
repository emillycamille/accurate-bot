<?php

use App\Models\Reminder;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $time = new Carbon('06-02-2021 10:00');

    $this->travelTo($time);
});

test('bot can send confirmation', function () {
    Http::fake([
        config('bot.translate_api_url') . '*' => Http::response([
            'status' => true,
            'message' => 'success',
            'data' => ['result' => 'tomorrow at 10:00'],
        ]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('remind makan - Besok pukul 10:00');

    $this->assertRequestSent();
});

test('bot can return exception', function () {
    Http::fake([
        config('bot.translate_api_url') . '*' => Http::response([
            'status' => true,
            'message' => 'success',
            'data' => ['result' => 'tomorrow at 10'],
        ]),
        '*' => Http::response(),
    ]);

    $this->receiveMessage('remind makan - Besok pukul 10');

    $this->assertRequestSent();
});

test('bot can return exception if there is no "-"', function () {
    Http::fake();

    $this->receiveMessage('remind makan Besok pukul 10');

    $this->assertRequestSent();
});

test('bot can save reminder to database', function () {
    Http::fake();

    $user = User::factory()->create();
    $reminder = Reminder::factory()->make();

    $this->receivePostback('SET_REMINDER:PS_ID:2021-04-03 10:00:00//ACTION');

    test()->assertRequestSent();

    $this->assertDatabaseHas('users', ['first_name' => $user->first_name]);
    $this->assertDatabaseHas('reminders', [
        'action' => $reminder->action,
        'remind_at' => $reminder->remind_at,
        'first_name' => $reminder->first_name,
        'psid' => $reminder->psid,
    ]);
});
