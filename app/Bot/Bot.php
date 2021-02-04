<?php

namespace App\Bot;

use Illuminate\Support\Facades\Http;

class Bot
{
    /**
     * Handle received message event.
     */
    public static function receivedMessage(array $event): void
    {
        // TODO: Create MessagingEvent interface.

        $message = $event['message']['text'];

        if ($message === "1+1") {
            $reply = "2";
        }

        else {
            $reply = "I'm still learning, so I don't understand '$message' yet. Chat with me again in a few days!";
        }

        static::sendMessage($reply, $event['sender']['id']);
    }

    /**
     * Send $message to $recipient using Messenger Send API.
     * https://developers.facebook.com/docs/messenger-platform/send-messages/#send_api_basics.
     */
    public static function sendMessage(string $message, string $recipient): void
    {
        $data = [
            'messaging_type' => 'RESPONSE',
            'recipient' => ['id' => $recipient],
            'message' => ['text' => $message],
        ];

        Http::post(config('bot.fb_sendapi_url'), $data);
    }
}
