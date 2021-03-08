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
    public static function salesInvoice(string $psid): void
    {
        $items = static::askAccurate($psid, 'sales-invoice/list.do');

        if (count($items['d']) == 0) {
            static::sendMessage('Kakak belum ada penjualan. Tetap semangat ya kak :)', $psid);

            return;
        }

        if (count($items['d']) < 5) {
            $count = count($items['d']);
            
        } else {
            $count = 5;
            
        }

        $message = sprintf('Berikut %d Transaksi Penjualanmu:', $count )."\n\n";
            for ($i = 0; $i <= $count-1; $i++) {
                $message .= sprintf('%d. ', $i + 1);
                $message .= sprintf('%s - %s %s (%s)',
                $items['d'][$i]['transDate'],
                $items['d'][$i]['customer']['name'],
                idr($items['d'][$i]['totalAmount']),
                $items['d'][$i]['statusName']);
                $message .= "\n";
            }
            static::sendMessage($message, $psid);
    }

}
