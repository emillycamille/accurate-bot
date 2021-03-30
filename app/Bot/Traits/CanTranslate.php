<?php

namespace App\Bot\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

trait CanTranslate
{
    /**
     * Determine whether the $message is asking to translate.
     */
    public static function isAskingToTranslate(string $message): bool
    {
        return Str::contains(strtolower($message), ['translate', 'terjemahkan']);
    }

    /**
     * Tell the translated text, as requested in $message.
     */
    public static function doTranslate(string $message): string
    {
        $message = strtolower($message);

        foreach (['translate', 'terjemahkan'] as $needle) {
            if (Str::contains($message, $needle)) {
                $text = trim(Str::after($message, $needle));

                if (! $text) {
                    return __('bot.unknown_translate');
                }

                $response = Http::get(config('bot.translate_api_url'), [
                    'engine' => 'google',
                    'text' => $text,
                    'to' => 'en',
                ]);

                return $response['data']['result'];
            }
        }
    }
}
