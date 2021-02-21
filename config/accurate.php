<?php

$clientId = env('ACCURATE_CLIENT_ID');

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
     * URL to login to Accurate.
     */
    'login_url' => 'https://accurate.id/oauth/authorize?'.http_build_query([
        'client_id' => $clientId,
        'response_type' => 'code',
        'redirect_uri' => 'https://accurate-bot.herokuapp.com/accurate-callback',
        'scope' => 'item_view item_save sales_invoice_view',
    ]),
];
