<?php

namespace App\Bot\Traits\Accurate;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait CanManageCustomers
{
    /**
     * Find $customerId in Accurate and reply back with the customer detail.
     */
    public static function detailCustomer(string $psid, int | string $customerId): void
    {
        $customer = static::askAccurate($psid, 'customer/detail.do', [
            'id' => $customerId,
        ])['d'];

        static::sendMessage(static::customerToString($customer), $psid);
    }

    /**
     * Determines whether the user is asking about customer detail. If yes,
     * return the keyword of the customer being asked.
     */
    public static function isAskingCustomerDetail(string $message): false | string
    {
        $message = strtolower($message);

        foreach (['customer', 'pelanggan'] as $needle) {
            if (Str::contains($message, $needle)) {
                // Return ' ' (space) if keyword is not given.
                return trim(Str::after($message, $needle)) ?: ' ';
            }
        }

        return false;
    }

    /**
     * Format $customer to a string containing its details.
     */
    public static function customerToString(array $customer): string
    {
        $phones = array_filter(Arr::only($customer, ['mobilePhone', 'workPhone']));
        $phones = implode('/', $phones);
        $time = now()->format('d/m/Y H:i:s');

        return sprintf(
            "%s\n%s\n%s\n%s",
            $customer['name'],
            $phones,
            __('bot.outstanding', compact(['time'])),
            idr(data_get($customer, 'balanceList.0.balance', 0)),
        );
    }

    /**
     * List customers that matches the $keyword.
     */
    public static function listCustomer(string $psid, string $keyword): void
    {
        $keyword = trim($keyword);

        if (! $keyword) {
            static::sendMessage(__('bot.unknown_customer'), $psid);

            return;
        }

        $customers = static::askAccurate($psid, 'customer/list.do', [
            'fields' => 'id,name,balanceList,mobilePhone,workPhone',
            'filter.keywords.op' => 'CONTAIN',
            'filter.keywords.val' => $keyword,
            // FB allows max 13 quick replies.
            'sp.pageSize' => 13,
        ]);

        if (is_null($customers = data_get($customers, 'd'))) {
            return;
        }

        if (empty($customers)) {
            $payload = __('bot.no_customers_match_keyword', compact('keyword'));
        } elseif (count($customers) === 1) {
            $payload = static::customerToString($customers[0]);
        } else {
            $text = __('bot.multiple_customers_match_keyword')."\n";
            $buttons = [];

            foreach ($customers as $i => $customer) {
                $i++;

                $text .= "$i. {$customer['name']}\n";

                $buttons[] = [
                    'title' => $i,
                    'payload' => "DETAIL_CUSTOMER:$psid:{$customer['id']}",
                ];
            }
            $text .= "\n".__('bot.choices');
            $payload = static::makeQuickRepliesPayload($text, $buttons);
        }

        static::sendMessage($payload, $psid);
    }
}
