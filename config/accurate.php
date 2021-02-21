<?php

$clientId = env('ACCURATE_CLIENT_ID');

$callbackUrl = 'https://accurate-bot.herokuapp.com/auth/callback';

return [
    /*
     * Accurate client_id for this app, to enable this app to use Accurate API.
     */
    'client_id' => $clientId,

    /*
     * Accurate client_secret for this app, to enable this app to use Accurate API.
     */
    'client_secret' => env('ACCURATE_CLIENT_SECRET'),

    /*
     * URL to get Accurate access token.
     */
    'access_token_url' => 'https://account.accurate.id/oauth/token',

    'callback_url' => $callbackUrl,

    /*
     * URL to login to Accurate.
     */
    'login_url' => 'https://accurate.id/oauth/authorize?'.http_build_query([
        'client_id' => $clientId,
        'response_type' => 'code',
        'redirect_uri' => $callbackUrl,
        'scope' => 'item_view item_save sales_invoice_view',
    ]),
];
