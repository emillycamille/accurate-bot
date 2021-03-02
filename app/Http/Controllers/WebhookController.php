<?php

namespace App\Http\Controllers;

use App\Bot\Bot;
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
        // If `verify_token` is valid, ...
        if (
            $request->get('hub_mode') === 'subscribe'
            && $request->get('hub_verify_token') === config('bot.fb_verify_token')
        ) {
            // ... return back the challenge.
            return response($request->get('hub_challenge'));
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
        $entries = $request->entry;

        foreach ($entries as $entry) {
            $messagingEvent = $entry['messaging'][0];

            if (array_key_exists('message', $messagingEvent)) {
                Bot::receivedMessage($messagingEvent);
            } elseif (array_key_exists('postback', $messagingEvent)) {
                Bot::receivedPostback($messagingEvent);
            }
        }

        return response('');
    }
}
