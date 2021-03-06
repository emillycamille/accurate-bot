<?php

namespace App\Bot\Traits\Accurate;

use Illuminate\Support\Str;

trait CanShowSales
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

        $count = 1;

        $message = sprintf('{%s} Berikut 5 Transaksi Pembelianmu:\n', static::greetUser($psid));

        foreach ($items['d'] as $id => $number) {
            if ($count == 5) {
                break;
            }
            $message .= sprintf('{%d}. ', $count);
            $message .= static::getSalesInvoice($psid, $number);
            $message .= "\n";
            $count += 1;
        }

        static::sendMessage($message, $psid);
    }

    public static function getSalesInvoice(string $psid, int $id): string
    {
        $items = static::askAccurate($psid, 'sales-invoice/detail.do', [
            'id' => $id,
        ]);
        $date = $items['d']['transDateView'];
        $name = $items['d']['detailItem'][0]['detailName'];
        $quantity = $items['d']['detailItem'][0]['quantity'];
        $price = $items['d']['detailItem'][0]['salesAmount'];
        $message = sprintf('({%s}) {%d} {%s} Rp{%d}', $date, $quantity, $name, $price);

        return $message;
    }
}
