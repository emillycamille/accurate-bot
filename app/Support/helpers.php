<?php

use Illuminate\Support\Str;

if (! function_exists('idr')) {
    /**
     * Format a number in IDR currency.
     */
    function idr(int | string $amount): string
    {
        return 'Rp'.number_format($amount, 0, ',', '.');
    }
}

if (! function_exists('make_replacements')) {
    function make_replacements($line, array $replace)
    {
        if (empty($replace)) {
            return $line;
        }

        foreach ($replace as $key => $value) {
            $line = str_replace(
                [':'.$key, ':'.Str::upper($key), ':'.Str::ucfirst($key)],
                [$value, Str::upper($value), Str::ucfirst($value)],
                $line
            );
        }

        return $line;
    }
}
