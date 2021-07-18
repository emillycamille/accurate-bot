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

        $response = static::askAccurate($psid, 'db-list.do');

        if (! array_key_exists('d', $response)) {
            return $response;
        }

        $dbs = $response['d'];

        if (empty($dbs)) {
            return static::login($params, __('bot.no_db'));
        }

        // Send postback buttons so user can choose which DB to open.
        $payload = static::makeQuickRepliesPayload(
            $template,
            array_map(function ($db) {
                return [
                    'title' => $db['alias'],
                    'payload' => 'openDb:'.$db['id'],
                ];
            }, $dbs)
        );

        return $payload;
    }

    /**
     * Open an Accurate DB and save the host and session data.
     */
    public static function openDb(array $params, string $template): string
    {
        $psid = $params['psid'];
        $id = $params['dbId'];

        User::updateOrCreate(
            ['psid' => $psid],
            ['database_id' => $id],
        );

        $data = static::askAccurate($psid, 'open-db.do', compact('id'));

        // Save the host and session to DB, because they will be needed
        // for the next Accurate requests.
        User::where('psid', $psid)->update([
            'host' => $data['host'],
            'session' => $data['session'],
            'database_id' => $id,
        ]);

        return $template;
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
