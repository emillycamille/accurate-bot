<?php

$fbPageToken = env('FB_PAGE_TOKEN');

return [
    'accurate_login_url' => sprintf(
        'https://accurate.id/oauth/authorize?client_id=%s&response_type=code&redirect_uri=%s&scope=item_view+item_save+sales_invoice_view',
        'b83fe634-316e-457b-a8db-f389e7e6807c',
        'https%3A%2F%2Faccurate-bot.herokuapp.com%2Faccurate-callback'
    ),

    /*
     * Token to allow this app to send message using Messenger Send API.
     */
    'fb_page_token' => $fbPageToken,

    /*
     * Send API endpoint URL.
     */
    'fb_sendapi_url' => sprintf(
        'https://graph.facebook.com/v9.0/me/messages?access_token=%s',
        $fbPageToken,
    ),

    /*
     * Token generated by us to verify that the webhook is valid and usable by Messenger.
     */
    'fb_verify_token' => env('FB_VERIFY_TOKEN'),
];
