<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FactoryDbStatus extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'factory:db-status';

    /**
     * The console command description.
     */
    protected $description = 'Show whether this app is connected to the shared database or running disconnected.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $shared = (bool) config('factory.shared_db_enabled');

        $this->info($shared
            ? 'Shared database: CONNECTED (enabled)'
            : 'Shared database: DISCONNECTED (local mode)');
        $this->line('Connection: '.config('database.default'));

        if ($shared) {
            $this->line('Schema: '.config('factory.schema'));
            $this->line('Host: '.config('database.connections.pgsql.host'));
            $this->line('Database: '.config('database.connections.pgsql.database'));
        }

        try {
            $pdo = DB::connection()->getPdo();
            $this->info('Database reachable: yes ('.$pdo->getAttribute(\PDO::ATTR_DRIVER_NAME).')');
            $this->line('Users table: '.(Schema::hasTable('users') ? 'present' : 'missing — run php artisan migrate'));

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Database reachable: no — '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
