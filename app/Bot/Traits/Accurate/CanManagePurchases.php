<?php

namespace App\Bot\Traits\Accurate;

use Illuminate\Support\Str;

trait CanManagePurchases
{
    /**
     * Determines whether the user is asking about purchase invoice.
     */
    public static function isAskingPurchaseInvoice(string $message): bool
    {
        return Str::contains(strtolower($message), ['purchase', 'pembelian']);
    }

    /**
     * List last 5 purchase invoices.
     */
    public static function showPurchaseInvoice(string $psid, int $page, string $message = 'histori pembelian'): void
    {
        // If $message contains a date, show TOTAL purchase of that date.
        $date = Str::of($message)->match('/\d{1,2}\/\d{1,2}\/\d{2,4}/');

        if ($date->isNotEmpty()) {
            static::showTotalPurchase($psid, $date);

            return;

        // If $message does not contain "history", return the TOTAL purchase at that moment.
        } elseif (! Str::contains(strtolower($message), ['history', 'histori'])) {
            static::showTotalPurchase($psid, now()->format('d/m/Y'));

            return;
        }

        $items = static::askAccurate($psid, 'purchase-invoice/list.do', [
            'fields' => 'transDate,totalAmount,statusName,vendor',
            'sp.page' => $page,
            'sp.pageSize' => '5',
        ]);

        if (count($items['d']) == 0) {
            static::sendMessage(__('bot.no_purchases'), $psid);

            return;
        }

        // Show purchase HISTORY.
        $message = sprintf(__('bot.show_purchases_title'), count($items['d']))."\n\n";

        foreach ($items['d'] as $key => $value) {
            $message .= sprintf('%d. ', (5 * (int) $page - 4) + $key);
            $message .= sprintf(
                '%s %s %s (%s)'."\n",
                $items['d'][$key]['transDate']."\n",
                $items['d'][$key]['vendor']['name']."\n",
                idr($items['d'][$key]['totalAmount'])."\n",
                $items['d'][$key]['statusName']
            );
            $message .= "\n";
        }

        $message .= sprintf(__('bot.page', compact('page')));
        static::sendMessage($message, $psid);

        // Offer to show next page
        if ($items['sp']['pageCount'] > (int) $page) {
            $page++;
            $payload = static::makeButtonPayload(__('bot.ask_next_page'), [[
                'type' => 'postback',
                'title' => __('bot.yes'),
                'payload' => "SHOW_PURCHASE_INVOICE:$psid:$page",
            ]]);

            static::sendMessage($payload, $psid);
        }
    }

    /**
     * Show total purchase at the requested date.
     */
    public static function showTotalPurchase(string $psid, string $date): void
    {
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

            $page++;
        } while ($page <= $items['sp']['pageCount']);

        $amount = idr($amount);

        $message = __('bot.total_purchase_at', compact('date', 'amount'));

        static::sendMessage($message, $psid);
    }
}
