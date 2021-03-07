<?php

namespace App\Bot\Traits\Accurate;

use Illuminate\Support\Str;
use App\Bot\Traits\CanGreetUser;

trait CanShowPurchase
{
    use CanGreetUser;
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
        $items = static::askAccurate($psid, 'purchase-invoice/list.do');

        static::sendMessage("Tunggu sebentar ya kak :)", $psid);

        $message = sprintf('%s Berikut 5 Transaksi Pembelianmu:', static::greetUser("",$psid))."\n\n";

        
        if (count($items['d']) == 0) {
            static::sendMessage("Kakak belum ada pembelian saat ini :)",$psid);
            return;
        }

        if (count($items['d']) < 5) {
        for ($i =0 ; $i <= count($items['d'])-1 ; $i++) {
            $id = $items['d'][$i]["id"];
            $message .= sprintf('%d. ', $i+1);
            $message .= static::getPurchaseInvoice($psid, $id);
            $message .= "\n";

        }
    }
        else {
        for ($i =0 ; $i <= 4; $i++) {
            $id = $items['d'][$i]["id"];
            $message .= sprintf('%d. ', $i+1);
            $message .= static::getPurchaseInvoice($psid, $id);
            $message .= "\n";

        }
    }
        static::sendMessage($message, $psid);

    }

    public static function getPurchaseInvoice(string $psid, int $id): string
    {
        $items = static::askAccurate($psid, 'purchase-invoice/detail.do', [
            'id' => $id,
        ]);
        $date = $items['d']['transDateView'];
        $name = $items['d']['detailItem'][0]['detailName'];
        $quantity = $items['d']['detailItem'][0]['quantity'];
        $price = $items['d']['detailItem'][0]['itemCost'];
        $message = sprintf('(%s) %d %s Rp%d', $date, $quantity, $name, $price);

        return $message;
    }
}
