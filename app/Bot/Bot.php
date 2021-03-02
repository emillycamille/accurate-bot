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
     * Return an array that can be used as button payload for `sendMessage`.
     */
    public static function makeButtonPayload(string $text, array $buttons): array
    {
        return [
            'attachment' => [
                'type' => 'template',
                'payload' => [
                    'template_type' => 'button',
                    'text' => $text,
                    'buttons' => $buttons,
                ],
            ],
        ];
    }

    /**
     * Handle received message event.
     */
    public static function receivedMessage(array $event): void
    {
        // TODO: Create MessagingEvent interface.

        $message = $event['message']['text'];

        $senderId = $event['sender']['id'];

        if (static::isRequestingLogin($message)) {
            $reply = static::sendLoginButton($senderId);
        } elseif (static::isMathExpression($message)) {
            $reply = static::calculateMathExpression($message);
        } elseif (static::isAskingTime($message)) {
            $reply = static::tellTime($message);
        } elseif (static::isAskingWeather($message)) {
            $reply = static::tellWeather($message);
        } elseif (static::isSayingHello($message)) {
            $reply = static::greetUser($message, $senderId);
        } else {
            $reply = "I'm still learning, so I don't understand '$message' yet. Chat with me again in a few days!";
        }

        static::sendMessage($reply, $senderId);
    }

    /**
     * Handle received postback event.
     */
    public static function receivedPostback(array $event): void
    {
    }

    /**
     * Send $payload to $recipient using Messenger Send API.
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
