<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebhookController
{
    /**
     * Verify the webhook for Messenger.
     * https://developers.facebook.com/docs/messenger-platform/webhook#setup.
     */
    public function verify(Request $request): Response
    {
        return response();
    }
}
