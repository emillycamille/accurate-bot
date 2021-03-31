<?php

namespace App\Bot\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait CanShowWikipedia
{
    /**
     * Tell the other trait based on Wikipedia.
     */
    public static function showWikipedia(string $message): false | string
    {
        if (Str::contains(strtolower($message), ['apa', 'mana'])) {
            return false;
        }

        $response = Http::get(config('bot.wikipedia_api_url'), [
        'action' => 'query',
        'format' => 'json',
        'list' => 'search',
        'formatversion' => 'latest',
        'srsearch' => $message,
        'srlimit' => '1',
        ])->throw();

        $result = data_get($response, 'query.search.0.snippet');

        if (is_null($result)) {
            return false;
        }

        return strip_tags($result);
    }
}
