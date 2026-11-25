<?php

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Stripe billing (Laravel Cashier)
    |--------------------------------------------------------------------------
    |
    | Cashier reads these keys. Use TEST keys (pk_test_ / sk_test_) until
    | launch. STRIPE_PRICE_MONTHLY / STRIPE_PRICE_YEARLY are the Stripe
    | Price IDs for the app's plans — create the products and prices in
    | the Stripe dashboard first, then paste the IDs here.
    |
    */

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'price_monthly' => env('STRIPE_PRICE_MONTHLY'),
        'price_yearly' => env('STRIPE_PRICE_YEARLY'),
        // Robert plans: Basic $7/month, Unlimited $47/month.
        'price_basic' => env('STRIPE_PRICE_BASIC'),
        'price_unlimited' => env('STRIPE_PRICE_UNLIMITED'),
    ],

    /*
    |--------------------------------------------------------------------------
    | WorkOS AuthKit
    |--------------------------------------------------------------------------
    |
    | Same values as config/workos.php, kept here so services that expect
    | them under services.workos keep working.
    |
    */

    'workos' => [
        'client_id' => env('WORKOS_CLIENT_ID'),
        'api_key' => env('WORKOS_API_KEY'),
        'redirect_url' => env('WORKOS_REDIRECT_URL'),
    ],

];
