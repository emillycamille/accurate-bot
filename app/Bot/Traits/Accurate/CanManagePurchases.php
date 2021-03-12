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
    public static function purchaseInvoice(string $psid): void
    {
        $items = static::askAccurate($psid, 'purchase-invoice/list.do', [
            'fields' => 'transDate,totalAmount,statusName,vendor',
        ]);

        if (count($items['d']) == 0) {
            static::sendMessage(__('bot.no_purchases'), $psid);

            return;
        }

        if (count($items['d']) < 5) {
            $count = count($items['d']);
        } else {
            $count = 5;
        }

        $message = sprintf(__('bot.show_purchases_title'), $count) . "\n\n";
        for ($i = 0; $i <= $count - 1; $i++) {
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
    }
}
