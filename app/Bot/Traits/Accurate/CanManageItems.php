<?php

namespace App\Bot\Traits\Accurate;

use Illuminate\Support\Str;

trait CanManageItems
{
    /**
     * Find $itemId in Accurate and reply back with the item detail.
     */
    public static function detailItem(string $psid, int | string $itemId): void
    {
        $item = static::askAccurate($psid, 'item/detail.do', [
            'id' => $itemId,
        ])['d'];

        static::sendMessage(static::itemToString($item), $psid);

        // If there's an image, offer to show that image.
        if ($image = data_get($item, 'detailItemImage.0.fileName')) {
            $payload = static::makeButtonPayload('',[[
                    'type' => 'postback',
                    'title' => __('bot.show_image'),
                    'payload' => "SHOW_IMAGE:$psid:$image",
                ]],
            );

            static::sendMessage($payload, $psid);
        }
    }
    
    /**
     * Determines whether the user is asking about item detail. If yes,
     * return the keyword of the item being asked.
     */
    public static function isAskingItemDetail(string $message): false | string
    {
        $message = strtolower($message);
        
        if (! Str::contains($message, 'item')) {
            return false;
        }

        return trim(Str::after($message, 'item'));
    }

    /**
     * Format $item to a string containing its name, price, and stock.
     */
    public static function itemToString(array $item): string
    {
        return sprintf(
            "%s\n%s: %s\n%s: %s",
            $item['name'],
            __('bot.price'), idr($item['unitPrice']),
            __('bot.stock'), $item['availableToSell'],
        );
    }

    /**
     * List items that matches the $keyword.
     */
    public static function listItem(string $psid, string $keyword): void
    {
        $items = static::askAccurate($psid, 'item/list.do', [
            'fields' => 'id,name,availableToSell,unitPrice',
            'filter.keywords.op' => 'CONTAIN',
            'filter.keywords.val' => $keyword,
            // FB allows max 13 quick replies.
            'sp.pageSize' => 13,
        ]);

        if (is_null($items = data_get($items, 'd'))) {
            return;
        }

        if (empty($items)) {
            $payload = __('bot.no_items_match_keyword', compact('keyword'));
        } elseif (count($items) === 1) {
            $payload = static::itemToString($items[0]);
        } else {
            $text = __('bot.multiple_items_match_keyword')."\n\n";
            $buttons = [];

            foreach ($items as $i => $item) {
                $i++;

                $text .= "$i. {$item['name']}\n";

                $buttons[] = [
                    'title' => $i,
                    'payload' => "DETAIL_ITEM:$psid:{$item['id']}",
                ];
            }

            $payload = static::makeQuickRepliesPayload($text, $buttons);
        }

        static::sendMessage($payload, $psid);
    }
}
