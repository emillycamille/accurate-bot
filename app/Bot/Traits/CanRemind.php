<?php

namespace App\Bot\Traits;

use App\Models\Reminder;
use App\Models\User;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Str;

trait CanRemind
{
    use CanTranslate;

    /**
     * Determine whether $message is asking to remind.
     */
    public static function isAskingToRemind(string $message): array | bool
    {
        $message = strtolower($message);

        foreach (['ingatkan', 'remind'] as $needle) {
            if (Str::contains($message, $needle)) {

                // If the user does not write "-", make the default action and time an empty string.
                // This empty strings are processed in the confirmReminder function.
                if (! Str::contains($message, '-')) {
                    return ['', ''];
                }
                // Explode message to grab the information.
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
        // If the user does not write "-", the bot send the correct format example.
        if ([$action, $time] == ['', '']) {
            static::sendMessage(__('bot.wrong_reminder_format')."\n\n".
                __('bot.remind_adding_dash'), $psid);

            return;
        }

        try {
            // Translate the time to English.
            $translatedTime = static::doTranslate('translate '.$time);

            // Change time to Carbon format.
            $carbonTime = Carbon::parse($translatedTime);
            $date = $carbonTime->format('d F Y');
            $time = $carbonTime->format('H:i');
            $action = ucfirst($action);
            $parsedInformation = $carbonTime.'//'.$action;

            // Return confirmation message.
            static::sendMessage(__(
                'bot.reminder_confirmation',
                compact('action', 'date', 'time')
            ), $psid);

            // Confirm reminder.
            $payload = static::makeButtonPayload(__('bot.confirm_reminder'), [[
                'type' => 'postback',
                'title' => __('bot.yes'),
                'payload' => "SET_REMINDER:$psid:$parsedInformation",
            ]]);

            static::sendMessage($payload, $psid);
        } catch (InvalidFormatException $e) {

            // Catch invalid time format
            static::sendMessage(__('bot.wrong_reminder_format'), $psid);
        }
    }

    public static function setReminder(string $psid, string $parsedInformation): void
    {
        // Process $parsedInformation
        $time = Str::before($parsedInformation, '//');

        $remind_at = Carbon::parse($time);
        $action = Str::after($parsedInformation, '//');
        $first_name = User::firstWhere('psid', $psid)->first_name;

        // Save PSID, First Name, Action, and Remind At to reminders table
        Reminder::create(compact('action', 'remind_at', 'psid', 'first_name'));

        // Return success message.
        static::sendMessage(__('bot.reminder_created'), $psid);
    }
}
