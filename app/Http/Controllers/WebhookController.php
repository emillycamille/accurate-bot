<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class WebhookController
{
    use ValidatesRequests;

    /**
     * Verify the webhook for Messenger.
     * https://developers.facebook.com/docs/messenger-platform/webhook#setup.
     */
    public function verify(Request $request): Response
    {
        $data = $request->query('hub');

        // If `verify_token` is valid, ...
        if (
            data_get($data, 'mode') === 'subscribe'
            && data_get($data, 'verify_token') === config('bot.fb_verify_token')
        ) {
            // ... return back the challenge.
            return response(data_get($data, 'challenge'));
        }

        // Else, throw validation exception, which will result in 422 response.
        throw ValidationException::withMessages([]);
    }

    /**
     * Handle the webhook event sent by Messenger.
     * https://developers.facebook.com/docs/messenger-platform/reference/webhook-events.
     */
    public function handle(Request $request): Response
    {
        return response('');
    }
}
