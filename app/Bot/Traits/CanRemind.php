<?php

namespace App\Bot\Traits;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Str;

trait CanRemind
{
    use CanTranslate;

    /**
     * Determine whether $message is asking to remind.
     */
    public static function isAskingToRemind(string $message, string $psid): array | bool
    {
        $message = strtolower($message);

        foreach (['ingatkan', 'remind'] as $needle) {
            if (Str::contains($message, $needle)) {
                if (! Str::contains($message, '-')) {
                    return ['', ''];
                }
                // Explode message to grab the information
                $information = explode('-', trim(Str::after($message, $needle)));

                return [$information[0], $information[1]];
            }
        }

        return false;
    }

    /**
     * Process the message.
     */
    public static function confirmReminder(string $action, string $time, string $psid): void
    {
        if ([$action, $time] == ['', '']) {
            static::sendMessage(__('bot.reminder_exception')."\n\n".
                    'Jangan lupa tambahkan "-" ya 😊', $psid);

            return;
        }
        try {
            // Translate the time to English
            $translatedTime = static::doTranslate('translate '.$time);

            // Change time to now() or Carbon format
            $carbonTime = Carbon::parse($translatedTime);
            $date = $carbonTime->format('d F Y');
            $time = $carbonTime->format('H:i');
            $action = ucfirst($action);

            // Return confirmation message
            static::sendMessage(__('bot.reminder_confirmation',
            compact('action', 'date', 'time')), $psid);
        } catch (InvalidFormatException $e) {

            // Catch invalid time format
            static::sendMessage(__('bot.reminder_exception'), $psid);
        }
    }
}
