<?php

namespace App\Bot\Traits\Accurate;

use Illuminate\Support\Str;
use App\Bot\Traits\CanGreetUser;

trait CanShowSales
{
    use CanGreetUser;
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
        if (is_null($items = data_get($items, 'd'))) {
            return;
        }

        static::sendMessage("Tunggu sebentar ya kak :)", $psid);

        $message = sprintf('%s Berikut 5 Transaksi Penjualanmu:', static::greetUser("",$psid))."\n\n";

        if (count($items['d']) == 0) {
            static::sendMessage("Kakak belum ada penjualan. Tetap semangat ya kak :)",$psid);
            return;
        }

        if (count($items['d']) < 5) {
            for ($i =0 ; $i <= count($items['d'])-1; $i++) {
                $id = $items['d'][$i]["id"];
                $message .= sprintf('%d. ', $i+1);
                $message .= static::getSalesInvoice($psid, $id);
                $message .= "\n";
    
            }
        }
        else {

        for ($i =0 ; $i <= 4; $i++) {
            $id = $items['d'][$i]["id"];
            $message .= sprintf('%d. ', $i+1);
            $message .= static::getSalesInvoice($psid, $id);
            $message .= "\n";

        }
        static::sendMessage($message, $psid);
    }
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
        $message = sprintf('(%s) %d %s Rp%d', $date, $quantity, $name, $price);

        return $message;
    }
}
