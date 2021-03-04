<?php

namespace App\Bot\Traits\Accurate;

use Illuminate\Support\Str;

trait CanManageItems
{
    /**
     * Determines whether the user is asking about item detail. If yes,
     * return the keyword of the item being asked.
     */
    public static function isAskingItemDetail(string $message): false | string
    {
        if (! Str::contains($message, 'item')) {
            return false;
        }

        return trim(Str::after($message, 'item'));
    }

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
     * List 5 items that matches the $keyword.
     */
    public static function listItem(string $psid, string $keyword): void
    {
        $items = static::askAccurate($psid, 'item/list.do', [
            'fields' => 'id,name,availableToSell,unitPrice',
            'filter.keywords.op' => 'CONTAIN',
            'filter.keywords.val' => $keyword,
            'sp.pageSize' => 5,
        ]);

        if (is_null($items = data_get($items, 'd'))) {
            return;
        }

        if (empty($items)) {
            $payload = __('bot.no_items_match_keyword', compact('keyword'));
        } elseif (count($items) === 1) {
            $payload = static::itemToString($items[0]);
        } else {
            $payload = static::makeQuickRepliesPayload(
                __('bot.multiple_items_match_keyword'),
                array_map(function ($item) use ($psid) {
                    return [
                        'title' => $item['name'],
                        'payload' => "DETAIL_ITEM:$psid:{$item['id']}",
                    ];
                }, $items)
            );
        }

        static::sendMessage($payload, $psid);
    }
}
