<?php

namespace App\Bot\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait CanShowDefinition
{
    /**
     * Determine whether the $message is asking definition.
     */
    public static function isAskingDefinition(string $message): false | string
    {
        $message = strtolower($message);

        foreach (['definisi', 'arti', 'arti kata'] as $needle) {
            if (Str::contains($message, $needle)) {
                // Return ' ' (space) if item keyword is not given.
                return trim(Str::after($message, $needle)) ?: ' ';
            }
        }

        return false;
    }

    /**
     * Tell the current weather, as requested in $message.
     */
    public static function showDefinition(string $message): string
    {
        $response = Http::get(config('bot.definition_api_url'), [
            'format' => 'json',
            'phrase' => $message,
        ])->throw();

        if (is_null($response->json())) {
            return __('bot.fallback_reply', compact('message'));
        }

        $definition = data_get($response, 'kateglo.definition.0.def_text');

        return __('bot.definition', compact('message', 'definition'));
    }
}
