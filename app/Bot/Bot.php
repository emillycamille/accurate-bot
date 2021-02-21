<?php

namespace App\Bot;

use App\Bot\Traits\CanConnectAccurate;
use App\Bot\Traits\CanDoMath;
use App\Bot\Traits\CanGreetUser;
use App\Bot\Traits\CanTellTime;
use App\Bot\Traits\CanTellWeather;
use Illuminate\Support\Facades\Http;

class Bot
{
    use CanDoMath, CanTellTime, CanTellWeather, CanGreetUser, CanConnectAccurate;

    /**
     * Handle received message event.
     */
    public static function receivedMessage(array $event): void
    {
        // TODO: Create MessagingEvent interface.

        $message = $event['message']['text'];

        if (static::isLoginRequest($message)) {
            $reply = static::sendLoginButton();
        } elseif (static::isMathExpression($message)) {
            $reply = static::calculateMathExpression($message);
        } elseif (static::isAskingTime($message)) {
            $reply = static::tellTime($message);
        } elseif (static::isAskingWeather($message)) {
            $reply = static::tellWeather($message);
        } elseif (static::isSayingHello($message)) {
            $reply = static::greetUser($message, $event['sender']['id']);
        } else {
            $reply = "I'm still learning, so I don't understand '$message' yet. Chat with me again in a few days!";
        }

        static::sendMessage($reply, $event['sender']['id']);
    }

    /**
     * Send $message to $recipient using Messenger Send API.
     * https://developers.facebook.com/docs/messenger-platform/send-messages/#send_api_basics.
     */
    public static function sendMessage($payload, string $recipient): void
    {
        if (is_string($payload)) {
            $payload = ['text' => $payload];
        }

        $data = [
            'messaging_type' => 'RESPONSE',
            'recipient' => ['id' => $recipient],
            'message' => $payload,
        ];

        Http::post(config('bot.fb_sendapi_url'), $data);
    }
}
