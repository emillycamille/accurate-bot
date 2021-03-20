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

    public static function isAskingSalesInvoiceWithDate(string $message): bool
    {
        return Str::contains($message, ['sales', 'penjualan']) && Str::contains($message, '/');
    }

    /**
     * List last 5 sales invoices.
     */
    // CHANGE: $page pastikan int
    public static function salesInvoice(string $psid, string $page): void
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

        $items = static::askAccurate($psid, 'sales-invoice/list.do', [
            'fields' => 'transDate,totalAmount,statusName,customer',
            'sp.page' => $page,
            'sp.pageSize' => '5',
        ]);

        if (count($items['d']) == 0) {
            static::sendMessage(__('bot.no_sales'), $psid);

            return;
        }

        $message = sprintf(__('bot.show_sales_title'), count($items['d']))."\n\n";

        foreach ($items['d'] as $key => $value) {
            $message .= sprintf('%d. ', (5 * (int) $page - 4) + $key);
            $message .= sprintf(
                '%s - %s %s (%s)',
                $items['d'][$key]['transDate'],
                $items['d'][$key]['customer']['name'],
                idr($items['d'][$key]['totalAmount']),
                $items['d'][$key]['statusName']
            );
            $message .= "\n";
        }
        $message .= sprintf(__('bot.page'), compact('page'));
        static::sendMessage($message, $psid);

        // CHANGE: Kasi comment ya.
        if ($items['sp']['pageCount'] > (int) $page) {
            $page += 1;
            $payload = static::makeButtonPayload(__('bot.ask_next_page'), [[
                'type' => 'postback',
                'title' => __('bot.yes'),
                'payload' => "SALES_INVOICE:$psid:$page",
            ]]);

            static::sendMessage($payload, $psid);
        }
    }

    public static function salesInvoiceWithDate(string $message, string $psid): void
    {
        $messageSplit = preg_split('/\s+/', $message);
        $date = end($messageSplit);
        $message = sprintf(__('bot.sales_date_title', compact('date')));
        $amount = 0;

        do {
            $page = 1;
            $items = static::askAccurate($psid, 'sales-invoice/list.do', [
                'fields' => 'totalAmount',
                'filter.dueDate.val' => $date,
                'page' => $page,
                ]);

            if (count($items['d']) == 0) {
                static::sendMessage(__('bot.no_sales_date', compact('date')), $psid);

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
