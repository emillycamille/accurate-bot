<?php

$clientId = env('ACCURATE_CLIENT_ID');

return [
    /*
     * Accurate API URL.
     */
    'api_url' => 'https://account.accurate.id/api/',

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

    'redirect_url' => env('APP_URL').'auth/callback',

    /*
     * URL to login to Accurate. Note that this is still missing a `redirect_uri`
     * query, which should be appended manually with the `psid`.
     */
    'login_url' => 'https://accurate.id/oauth/authorize?'.http_build_query([
        'client_id' => $clientId,
        'response_type' => 'code',
        'scope' => 'item_view purchase_invoice_view sales_invoice_view',
    ]),
];
