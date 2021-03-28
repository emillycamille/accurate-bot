<?php

namespace App\Bot\Traits\Accurate;

use Illuminate\Support\Str;

trait CanManageSales
{
    /**
     * Determines whether the user is asking about sales invoice. If yes,
     * return the keyword of the item being asked.
     */
    public static function isAskingSalesInvoice(string $message): bool
    {
        return Str::contains(strtolower($message), ['sales', 'penjualan']);
    }

    /**
     * List last 5 sales invoices.
     */
    public static function showSalesInvoice(string $psid, int $page, string $message = null): void
    {
        // CHANGE: try to make this function more DRY.
        // $transactions = [
        //     'salesInvoice' => [
        //         'api_url' => 'something',
        //         'title' => __('bot.titleForSalesInvoice'),
        //     ],
        //     'purchaseInvoice' => [
        //         'api_url' => 'purchase',
        //         'title' => __('bot.titleForSalesInvoice'),
        //     ],
        // ];

        // $type -> salesInvoice / purchaseInvoice.

        // $data = $transactions[$type];
        $date = Str::of($message)->match('/\d{1,2}\/\d{1,2}\/\d{2,4}/');

        // If $message contains a date, show TOTAL sales of that date.
        if ($date->isNotEmpty()) {
            static::showTotalSales($psid, $date);

            return;

        // If $message does not contain "history", return the TOTAL sales at that moment.
        } elseif (! Str::contains(strtolower($message), ['history', 'histori'])) {
            static::showTotalSales($psid, now()->format('d/m/Y'));

            return;
        }

        $items = static::askAccurate($psid, 'sales-invoice/list.do', [
            'fields' => 'transDate,totalAmount,statusName,customer',
            'sp.page' => $page,
            'sp.pageSize' => '5',
        ]);

        if (count($items['d']) == 0) {
            static::sendMessage(__('bot.no_sales'), $psid);

            return;
        }

        // Show sales HISTORY.
        $message = sprintf(__('bot.show_sales_title'), count($items['d']))."\n\n";

        foreach ($items['d'] as $key => $value) {
            $message .= sprintf('%d. ', (5 * (int) $page - 4) + $key);
            $message .= sprintf(
                '%s %s %s (%s)'."\n",
                $items['d'][$key]['transDate']."\n",
                $items['d'][$key]['customer']['name']."\n",
                idr($items['d'][$key]['totalAmount'])."\n",
                $items['d'][$key]['statusName']
            );
            $message .= "\n";
        }
        $message .= sprintf(__('bot.page'), compact('page'));
        static::sendMessage($message, $psid);

        // Offer to show next page.
        if ($items['sp']['pageCount'] > (int) $page) {
            $page += 1;
            $payload = static::makeButtonPayload(__('bot.ask_next_page'), [[
                'type' => 'postback',
                'title' => __('bot.yes'),
                'payload' => "SHOW_SALES_INVOICE:$psid:$page",
            ]]);

            static::sendMessage($payload, $psid);
        }
    }

    /**
     * Show total sales at the requested date.
     */
    public static function showTotalSales(string $psid, string $date): void
    {
        $amount = 0;
        $page = 1;

        do {
            $items = static::askAccurate($psid, 'sales-invoice/list.do', [
                'fields' => 'totalAmount',
                'filter.dueDate.val' => $date,
                'page' => $page,
            ]);

            if (count($items['d']) == 0) {
                static::sendMessage(__('bot.no_sales_at', compact('date')), $psid);

                return;
            }

            foreach ($items['d'] as $key => $value) {
                $amount += $items['d'][$key]['totalAmount'];
            }

            $page++;
        } while ($page <= $items['sp']['pageCount']);

        $amount = idr($amount);

        $message = __('bot.total_sales_at', compact('date', 'amount'));

        static::sendMessage($message, $psid);
    }
}
