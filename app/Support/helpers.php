<?php

if (! function_exists('idr')) {
    /**
     * Format a number in IDR currency.
     */
    function idr(int | string $amount): string
    {
        return 'Rp'.number_format($amount, 0, ',', '.');
    }
}
