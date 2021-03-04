<?php

namespace App\Bot;

use App\Bot\Traits\CanConnectAccurate;
use App\Bot\Traits\CanDoMath;
use App\Bot\Traits\CanGreetUser;
use App\Bot\Traits\CanTellTime;
use App\Bot\Traits\CanTellWeather;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Bot
{
    use CanDoMath, CanTellTime, CanTellWeather, CanGreetUser, CanConnectAccurate;

    /**
     * Get the handler method (camelCase string) and payload of $postback event.
     */
    public static function getPostbackHandler(string $postback): array
    {
        // The postback payload is a string with this format: `HANDLER:PSID:PAYLOAD`.
        // We need to split them to variables, so the handler method can be called
        // (see `receivedPostback()`). The payload may not be always there, we use null
        // as default.
        [$handler, $psid, $payload] = explode(':', $postback, 3) + [2 => null];

        $handler = Str::camel(strtolower($handler));

        return [$handler, $psid, $payload];
    }

    /**
     * Return an array that can be used as button payload for `sendMessage`.
     */
    public static function makeButtonPayload(string $text, array $buttons): array
    {
        $buttons = array_map(function ($button) {
            // FB forbids button title longer than 20 characters.
            $button['title'] = Str::limit($button['title'], 20 - 2);

            return $button;
        }, $buttons);

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

        Log::debug("receivedMessage: $senderId: $message");

        if (static::isRequestingLogin($message)) {
            static::sendLoginButton($senderId);
        } elseif (static::isMathExpression($message)) {
            $reply = static::calculateMathExpression($message);
        } elseif (static::isAskingTime($message)) {
            $reply = static::tellTime($message);
        } elseif (static::isAskingWeather($message)) {
            $reply = static::tellWeather($message);
        } elseif (static::isSayingHello($message)) {
            $reply = static::greetUser($message, $senderId);
        } elseif ($keyword = static::isAskingItemDetail($message)) {
            static::listItem($senderId, $keyword);
        } else {
            $reply = "I'm still learning, so I don't understand '$message' yet. Chat with me again in a few days!";
        }

        if (isset($reply)) {
            static::sendMessage($reply, $senderId);
        }
    }

    /**
     * Handle received postback event.
     */
    public static function receivedPostback(array $event): void
    {
        $postback = $event['postback']['payload'];

        Log::debug("receivedPostback: $postback");

        [$handler, $psid, $payload] = static::getPostbackHandler($postback);

        static::$handler($psid, $payload);
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

        Log::debug("sendMessage: $recipient", $data + ["\n"]);

        Http::post(config('bot.fb_sendapi_url'), $data)->throw();
    }
}
