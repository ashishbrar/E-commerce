<?php

return [

    'mode' => env('PAYPAL_MODE', 'sandbox'),

    'sandbox' => [
        'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID'),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
        'app_id' => env('PAYPAL_SANDBOX_APP_ID'),
    ],

    'live' => [
        'client_id' => env('PAYPAL_LIVE_CLIENT_ID'),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET'),
        'app_id' => env('PAYPAL_LIVE_APP_ID'),
    ],

    'payment_action' => 'Sale',
    'currency' => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url' => '',
    'locale' => 'en_US',
    'validate_ssl' => true,
];
