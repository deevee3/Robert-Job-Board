<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection
    |--------------------------------------------------------------------------
    |
    | SHARED_DB_ENABLED=true  -> the app connects to the shared iMac-hosted
    | Postgres (DB_CONNECTION, normally pgsql).
    | SHARED_DB_ENABLED=false -> the app runs disconnected on local SQLite.
    |
    | The kit never installs or manages the database server; it only
    | connects to it. See config/factory.php and README.md.
    |
    */

    'default' => env('SHARED_DB_ENABLED', true)
        ? env('DB_CONNECTION', 'pgsql')
        : 'sqlite',

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            // DB_DATABASE names the shared Postgres database; it must
            // never be used as a SQLite file path. Disconnected mode
            // always uses this local file.
            'database' => database_path('database.sqlite'),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'factory_shared'),
            'username' => env('DB_USERNAME', 'factory_app'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            // Per-app namespace inside the shared database. One unique schema
            // per app (use the app slug). Tables live in this schema, so apps
            // sharing one database never collide.
            'search_path' => env('DB_SCHEMA', 'public'),
            'sslmode' => 'prefer',
        ],

    ],

    'migrations' => 'migrations',

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
