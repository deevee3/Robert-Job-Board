<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shared database (iMac-hosted Postgres)
    |--------------------------------------------------------------------------
    |
    | The factory backend is ONE shared database hosted on the iMac. Each
    | app connects to it or disconnects from it at will:
    |
    |   SHARED_DB_ENABLED=true  -> connect to the shared database.
    |   SHARED_DB_ENABLED=false -> run disconnected on local SQLite.
    |
    | The kit only connects. It never installs, starts, or manages the
    | database server — that is a separate setup step.
    |
    */

    'shared_db_enabled' => (bool) env('SHARED_DB_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Per-app schema (namespacing)
    |--------------------------------------------------------------------------
    |
    | One unique schema per app inside the shared database. Use the app slug
    | (e.g. "app05", "brain-shield"). All of the app's tables — users,
    | sessions, subscriptions, and the app's own tables — live in this
    | schema, so apps sharing one database never collide. The first
    | migration creates the schema if it does not exist yet.
    |
    */

    'schema' => env('DB_SCHEMA', 'app'),

    /*
    |--------------------------------------------------------------------------
    | App slug
    |--------------------------------------------------------------------------
    |
    | Short slug for this app (matches the factory project folder name).
    | Used for namespacing and logs.
    |
    */

    'slug' => env('APP_SLUG', 'factory-app'),

];
