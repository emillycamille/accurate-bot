<?php

namespace App\Bot;

use App\Bot\Traits\Accurate\CanConnectAccurate;
use App\Bot\Traits\CanDialogFlow;
use App\Bot\Traits\CanDoMath;
use App\Bot\Traits\CanGetStarted;
use App\Bot\Traits\CanGetWeather;
use App\Bot\Traits\CanGreetUser;
use App\Bot\Traits\CanRemind;
use App\Bot\Traits\CanShowDefinition;
use App\Bot\Traits\CanShowGoogle;
use App\Bot\Traits\CanShowHelp;
use App\Bot\Traits\CanShowWikipedia;
use App\Bot\Traits\CanTellTime;
use App\Bot\Traits\CanTranslate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Bot
{
    use CanDialogFlow,
        CanDoMath,
        CanTellTime,
        CanGetWeather,
        CanGreetUser,
        CanConnectAccurate,
        CanRemind,
        CanShowDefinition,
        CanShowGoogle,
        CanShowHelp,
        CanShowWikipedia,
        CanGetStarted,
        CanTranslate;

    /**
     * Get the handler method (camelCase string) and payload of $postback event.
     */
    public static function getPostbackHandler(string $postback, string $forcePsid = null): array
    {
        if (Str::contains($postback, ':')) {
            // The postback payload is a string with this format: `HANDLER:PSID:PAYLOAD`.
            // We need to split them to variables, so the handler method can be called
            // (see `receivedPostback()`). The payload may not be always there, we use null
            // as default.

            [$handler, $psid, $payload] = explode(':', $postback, 3) + [2 => null];
        } else {
            $handler = $postback;
            $payload = null;
        }

        $handler = Str::camel(strtolower($handler));

        if ($forcePsid) {
            $psid = $forcePsid;
        }

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
     * Return an array that can be used as quick replies payload for `sendMessage`.
     */
    public static function makeQuickRepliesPayload(string $text, array $items): array
    {
        if (count($items) > 13) {
            throw new \Exception('Quick replies may not exceed 13 items.');
        }

        $items = array_map(function ($item) {
            return [
                'content_type' => 'text',
                // FB forbids item title longer than 20 characters.
                'title' => Str::limit($item['title'], 20 - 2),
                'payload' => $item['payload'],
            ];
        }, $items);

        return [
            'text' => $text,
            'quick_replies' => $items,
        ];
    }

    /**
     * Handle received message event.
     */
    public static function receivedMessage(array $event): void
    {
        // TODO: Create MessagingEvent interface.

        if ($postback = data_get($event, 'message.quick_reply.payload')) {
            static::receivedPostback($postback);

            return;
        }

        $message = $event['message']['text'];

        $senderId = $event['sender']['id'];

        Log::debug("receivedMessage: $senderId: $message");

        if (static::isRequestingLogin($message)) {
            static::sendLoginButton($senderId);
        } elseif ($keyword = static::isAskingCustomerDetail($message)) {
            static::listCustomer($senderId, $keyword);
        } elseif ([$action, $time] = static::isAskingToRemind($message)) {
            $reply = static::confirmReminder($action, $time, $senderId);
        } elseif ($keyword = static::isAskingItemDetail($message)) {
            static::listItem($senderId, $keyword);
        } elseif (static::isAskingSwitchingDb($message)) {
            static::askWhichDb($senderId);
        } elseif (static::isAskingHelp($message)) {
            static::tellHelp($senderId);
        } elseif (static::isAskingPurchaseInvoice($message)) {
            static::showPurchaseInvoice($senderId, 1, $message);
        } elseif (static::isAskingSalesInvoice($message)) {
            static::showSalesInvoice($senderId, 1, $message);
        } elseif (static::isAskingToTranslate($message)) {
            $reply = static::doTranslate($message);
        } elseif (static::isSayingHello($message)) {
            $reply = static::greetUser($message, $senderId);
        } elseif (static::isAskingTime($message)) {
            $reply = static::tellTime($message);
        } elseif (static::isMathExpression($message)) {
            $reply = static::calculateMathExpression($message);
        } elseif ($keyword = static::isAskingDefinition($message)) {
            $reply = static::showDefinition($keyword);
        } elseif (static::isSendingNumber($message)) {
            static::sendMessage(__('bot.quick_reply_explanation'), $senderId);
        } else {
            $reply = __('bot.fallback_reply', compact('message'));
        }

        if (isset($reply)) {
            static::sendMessage($reply, $senderId);
        }
    }

    /**
     * Handle received postback event.
     */
    public static function receivedPostback(array | string $event): void
    {
        $postback = is_string($event) ? $event : $event['postback']['payload'];
        Log::debug("receivedPostback: $postback");

        [$handler, $psid, $payload] = static::getPostbackHandler(
            $postback,
            data_get($event, 'sender.id'),
        );

        static::$handler($psid, $payload);
    }

    /**
     * Send message (text or button) to $psid.
     */
    public static function sendMessage($payload, string $psid): void
    {
        if (is_string($payload)) {
            $payload = ['text' => $payload];
        }

        $data = [
            'messaging_type' => 'RESPONSE',
            'message' => $payload,
        ];

        static::sendToFb($psid, $data);
    }

    /**
     * Send $payload to $psid using Messenger Send API.
     * https://developers.facebook.com/docs/messenger-platform/send-messages/#send_api_basics.
     */
    public static function sendToFb(string $psid, array $payload): void
    {
        $payload += [
            'recipient' => ['id' => $psid],
        ];

        Log::debug("sendToFb: $psid", $payload + ["\n"]);

        Http::post(config('bot.fb_sendapi_url'), $payload)->throw();
    }

    /**
     * Trigger bot to show "typing on" on Messenger.
     */
    public static function typingOn(string $psid): void
    {
        // Typing on is turned off during testing to prevent extra snapshot for
        // each feature test.
        if (config('bot.typing_on')) {
            static::sendToFb($psid, ['sender_action' => 'typing_on']);
        }
    }
}
