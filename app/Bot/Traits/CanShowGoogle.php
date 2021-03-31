<?php

namespace App\Bot\Traits;

use Illuminate\Support\Facades\Http;

trait CanShowGoogle
{
    /**
     * Find the answer in SERP.
     */
    public static function showGoogleSearch(string $message): string | false
    {
        $response = Http::get(config('bot.serp_api_url'), [
            'api_key' => config('bot.serp_api_key'),
            'q' => $message,
            'gl' => 'id',
            'hl' => 'id',
            'location' => 'Indonesia',
            'google_domain' => 'google.co.id',
            'include_answer_box' => 'true',
            'output' => 'json',
            'include_html' => 'false',
            'flatten_results' => 'false',
            'filter' => '',
        ])->throw();

        $answer = data_get($response, 'answer_box.answers.0.answer');

        if (! is_null($answer)) {
            return $answer;
        }

        return false;
    }
}
