<?php

namespace App\Bot\Traits;

use Illuminate\Support\Str;

trait CanSwitchDb
{
    use CanConnectAccurate;
    /**
     * Determine whether the $message is asking to switch db.
     */
    public static function isAskingSwitchingDb(string $message): bool
    {
        return Str::contains(strtolower($message), ['ganti db','ganti database','switch db','switch database']);
    }

    public static function sendSwitchDb(string $psid): void
    {
        static::askWhichDb($psid);
    }
}
