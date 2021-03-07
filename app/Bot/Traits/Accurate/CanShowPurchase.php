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

        $count = 1;

        $message = sprintf('%s Berikut 5 Transaksi Penjualanmu:', static::greetUser("",$psid))."\n\n";

        foreach ($items['d'][0] as $id => $number) {
            if ($count <= 5) {
                $message .= sprintf('%d. ', $count);
                $string = static::getPurchaseInvoice($psid, $number);
                $message .= $string;
                $message .= "\n";
                $count = $count + 1;
                static::sendMessage($message, $psid);
            }
        }

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
