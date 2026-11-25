<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates this app's schema inside the shared database, if it does
     * not exist yet. The schema name comes from DB_SCHEMA (config
     * factory.schema) and must be a plain identifier — anything else is
     * rejected rather than interpolated into SQL.
     *
     * Runs only when the shared database is enabled and the connection
     * is Postgres. In disconnected (SQLite) mode there is nothing to do.
     */
    public function up(): void
    {
        if (! config('factory.shared_db_enabled')) {
            return;
        }

        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        $schema = (string) config('factory.schema');

        if (! preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $schema)) {
            throw new \InvalidArgumentException(
                "Invalid DB_SCHEMA [{$schema}]. Use letters, digits, and underscores only, starting with a letter or underscore."
            );
        }

        DB::statement('CREATE SCHEMA IF NOT EXISTS "'.$schema.'"');
    }

    /**
     * Reverse the migrations.
     *
     * Deliberately a no-op: dropping a schema destroys every table in it.
     * If an app is decommissioned, drop its schema by hand after checking
     * nothing else uses it.
     */
    public function down(): void
    {
        //
    }
};
