<?php

namespace App\Bot;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Bot
{
    /**
     * Handle received message event.
     */
    public static function receivedMessage(array $event): void
    {
        // TODO: Create MessagingEvent interface.

        $message = $event['message']['text'];

        // Define which operation is called
        // Assign reply with the result

        if (static::isMathExpression($message)) {
            $reply = static::calculateMathExpression($message);
        } elseif (static::isAskingTime($message)) {
            $reply = static::tellTime($message);
        } else {
            $reply = "I'm still learning, so I don't understand '$message' yet. Chat with me again in a few days!";
        }

        static::sendMessage($reply, $event['sender']['id']);
    }

    public static function isMathExpression(string $message): bool
    {
        return Str::contains($message, ['+', '-', '*', '/', 'x', ':', '÷']);
    }

    public static function calculateMathExpression(string $message): string
    {
        // Trim whitespace.
        $message = preg_replace('/\s+/', '', $message);

        // Extract the operator out of the message.
        foreach (['+', '-', '*', '/', 'x', ':', '÷'] as $sign) {
            if (Str::contains($message, $sign)) {
                $operator = $sign;

                break;
            }
        }

        // Find the 2 numbers to calculate.
        $pieces = explode($operator, $message);

        // Calculate according to the operator.
        switch ($operator) {
            case '+':
                return $pieces[0] + $pieces[1];

            case '-':
                return $pieces[0] - $pieces[1];

            case '*':
            case 'x':
                return $pieces[0] * $pieces[1];

            case '/':
            case ':':
            case '÷':
                return $pieces[0] / $pieces[1];
        }
    }

    public static function isAskingTime(string $message): bool
    {
        return Str::contains($message, ['hari', 'jam']);
    }

    public static function tellTime(string $message): string
    {
        $reply = '';

        if (Str::contains($message, 'hari')) {
            $reply .= now()->format('l ');
        }

        if (Str::contains($message, 'jam')) {
            $reply .= now()->format('H:i');
        }

        return $reply;
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
