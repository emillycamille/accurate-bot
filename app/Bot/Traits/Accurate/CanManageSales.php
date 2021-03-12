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
    public static function salesInvoice(string $psid, string $page): void
    {
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
        for ($i = 0; $i < count($items['d']); $i++) {
            $message .= sprintf('%d. ', $i + 1);
            $message .= sprintf(
                '%s - %s %s (%s)',
                $items['d'][$i]['transDate'],
                $items['d'][$i]['customer']['name'],
                idr($items['d'][$i]['totalAmount']),
                $items['d'][$i]['statusName']
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
                'payload' => "SALES_INVOICE:$psid:$page",
            ]]);

            static::sendMessage($payload, $psid);
        }
    }
}
