<?php

namespace App\Bot\Traits\Accurate;

use Illuminate\Support\Str;

trait CanManagePurchases
{
    /**
     * Determines whether the user is asking about purchase invoice. If yes,
     * return the keyword of the item being asked.
     */
    public static function isAskingPurchaseInvoice(string $message): bool
    {
        return Str::contains(strtolower($message), ['purchase', 'pembelian']);
    }

    /**
     * List last 5 purchase invoices.
     */
    public static function purchaseInvoice(string $psid, string $page): void
    {
        $items = static::askAccurate($psid, 'purchase-invoice/list.do', [
            'fields' => 'transDate,totalAmount,statusName,vendor',
            'sp.page' => $page,
            'sp.pageSize' => '5',
        ]);

        if (count($items['d']) == 0) {
            static::sendMessage(__('bot.no_purchases'), $psid);

            return;
        }

        $message = sprintf(__('bot.show_purchases_title'), count($items['d']))."\n\n";
        for ($i = 0; $i < count($items['d']); $i++) {
            $message .= sprintf('%d. ', $i + 1);
            $message .= sprintf(
                '%s - %s %s (%s)',
                $items['d'][$i]['transDate'],
                $items['d'][$i]['vendor']['name'],
                idr($items['d'][$i]['totalAmount']),
                $items['d'][$i]['statusName']
            );
            $message .= "\n";
        }

        static::sendMessage($message, $psid);

        if ($items['sp']['pageCount'] > $page) {
            $page += 1;
            $payload = static::makeButtonPayload(__('bot.ask_next_page'), [[
                'type' => 'postback',
                'title' => __('bot.yes'),
                'payload' => "PURCHASE_INVOICE:$psid:$page",
            ]]);

            static::sendMessage($payload, $psid);
        }
    }
}
