<?php

namespace App\Http\Controllers;

use App\Bot\Bot;
use App\Jobs\HandleWebhook;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
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
        // Handle the webhook event asynchronously.
        HandleWebhook::dispatchAfterResponse($request->entry);

        // This OK 200 response will always be returned because webhook event is handled
        // asynchronously.
        return response('');
    }

    public function fulfill(Request $request): Response
    {
        Log::debug('fromDf', $request->all() + ["\n"]);
        return response(Bot::fulfill($request));
    }
}
