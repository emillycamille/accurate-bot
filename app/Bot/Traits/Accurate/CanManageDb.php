<?php

namespace App\Bot\Traits\Accurate;

use App\Models\User;
use Illuminate\Support\Str;

trait CanManageDb
{
    /**
     * Ask user to choose which DB they want to open, by sending postbacks.
     */
    public static function askWhichDb(array $params, string $template): array
    {
        $psid = $params['psid'];
        $dbs = ['123456', '789104', '546372', '827389', '247714'];

        // if (empty($dbs)) {
        //     static::sendMessage(__('bot.no_db'), $psid);

        //     static::sendLoginButton($psid);

        //     return;
        // }

        // Send postback buttons so user can choose which DB to open.
        $payload = static::makeQuickRepliesPayload(
            $template,
            array_map(function ($db) use ($psid) {
                return [
                    'title' => $db,

                    // We should always include the $psid as the second payload,
                    // because FB won't include it in the `messaging_postback` event.
                    'payload' => "OPEN_DB:$db",
                ];
            }, $dbs)
        );

        return $payload;
    }

    public static function oldAskWhichDb(array $params, string $template): void
    {
        $dbs = static::askAccurate($psid, 'db-list.do')['d'];

        if (empty($dbs)) {
            static::sendMessage(__('bot.no_db'), $psid);

            static::sendLoginButton($psid);

            return;
        }

        // Send postback buttons so user can choose which DB to open.
        $payload = static::makeQuickRepliesPayload(
            __('bot.choose_db')."\n\n".__('bot.choose_options_below'),
            array_map(function ($db) use ($psid) {
                return [
                    'title' => $db['alias'],

                    // We should always include the $psid as the second payload,
                    // because FB won't include it in the `messaging_postback` event.
                    'payload' => "OPEN_DB:$psid:{$db['id']}",
                ];
            }, $dbs)
        );

        static::sendMessage($payload, $psid);
    }

    /**
     * Open an Accurate DB and save the host and session data.
     */
    public static function openDb(array $params, string $template): string
    {
        return $template.': '.$params['dbId'][0];
        // User::updateOrCreate(['psid' => $psid], ['database_id' => $id]);
        $data = static::askAccurate($psid, 'open-db.do', compact('id'));

        if ($data) {
            // Save the host and session to DB, because they will be needed
            // for the next Accurate requests.
            User::where('psid', $psid)->update([
                'host' => $data['host'],
                'session' => $data['session'],
                'database_id' => $id,
            ]);

            static::sendMessage(__('bot.db_opened'), $psid);
        }
    }

    /**
     * Determine whether the $message is asking to switch db.
     */
    public static function isAskingSwitchingDb(string $message): bool
    {
        return Str::contains(strtolower($message), [
            'ganti db', 'ganti database', 'switch db', 'switch database',
        ]);
    }
}
