<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WorkOS AuthKit
    |--------------------------------------------------------------------------
    |
    | Sign-in goes through WorkOS AuthKit (social login, magic links,
    | passkeys, SSO). Values come from the WorkOS dashboard (API Keys and
    | Redirects). The redirect URL registered in WorkOS must match
    | WORKOS_REDIRECT_URL exactly.
    |
    | The kit keeps the session in Laravel's own encrypted session
    | cookie. It stores no WorkOS access/refresh tokens, so there is no
    | sealed-session cookie password to set. An app that needs to
    | validate a WorkOS sealed session directly can use
    | UserManagement::loadSealedSession() /
    | UserManagement::authenticateWithSessionCookie() with its own
    | cookie password.
    |
    */

    'client_id' => env('WORKOS_CLIENT_ID'),

    'api_key' => env('WORKOS_API_KEY'),

    'redirect_url' => env('WORKOS_REDIRECT_URL', env('APP_URL').'/authenticate'),

];
