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
        return Str::contains($message, ['purchase', 'pembelian']);
    }

    public static function isAskingPurchaseInvoiceWithDate(string $message): bool
    {
        return Str::contains($message, ['purchase', 'pembelian']) && Str::contains($message, '/');
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

        foreach ($items['d'] as $key => $value) {
            $message .= sprintf('%d. ', (5 * (int) $page - 4) + $key);
            $message .= sprintf(
                '%s - %s %s (%s)',
                $items['d'][$key]['transDate'],
                $items['d'][$key]['vendor']['name'],
                idr($items['d'][$key]['totalAmount']),
                $items['d'][$key]['statusName']
            );
            $message .= "\n";
        }

        $message .= sprintf(__('bot.page'), $page);
        static::sendMessage($message, $psid);

        if ($items['sp']['pageCount'] > (int) $page) {
            $page += 1;
            $payload = static::makeButtonPayload(__('bot.ask_next_page'), [[
                'type' => 'postback',
                'title' => __('bot.yes'),
                'payload' => "PURCHASE_INVOICE:$psid:$page",
            ]]);

            static::sendMessage($payload, $psid);
        }
    }

    public static function purchaseInvoiceWithDate(string $message, string $psid): void
    {
        $messageSplit = preg_split('/\s+/', $message);
        $date = end($messageSplit);
        $message = sprintf(__('bot.purchases_date_title', compact('date')));
        $amount = 0;

        $page = 1;
        do {
            $items = static::askAccurate($psid, 'purchase-invoice/list.do', [
                'fields' => 'totalAmount',
                'filter.transDate.val' => $date,
                'page' => $page,
                ]);

            if (count($items['d']) == 0) {
                static::sendMessage(__('bot.no_purchases_date', compact('date')), $psid);

                return;
            }

            foreach ($items['d'] as $key => $value) {
                $amount += $items['d'][$key]['totalAmount'];
            }

            $page += 1;
            $pageCount = $items['sp']['pageCount'];
        } while ($page <= $pageCount);

        $amount = idr($amount);
        $message .= $amount;

        static::sendMessage($message, $psid);
    }
}
