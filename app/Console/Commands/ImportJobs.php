<?php

namespace App\Console\Commands;

use App\Models\JobPosting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;

class ImportJobs extends Command
{
    protected $signature = 'jobs:import {--path= : Path to the scraper jobs.db (defaults to JOB_SCRAPER_DB_PATH)}';

    protected $description = 'Import job postings from the scraper store into Apply, pruning anything older than 30 days';

    public function handle(): int
    {
        $path = $this->option('path')
            ?: env('JOB_SCRAPER_DB_PATH', '/home/hatch/workspace/your_files/job-scraper/jobs.db');

        if (! file_exists($path)) {
            $this->error("Scraper database not found at {$path}");

            return self::FAILURE;
        }

        $pdo = new PDO('sqlite:'.$path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $rows = $pdo->query(
            'SELECT id, source, company, title, location, url, posted_at, first_seen, salary, meta FROM jobs'
        )->fetchAll(PDO::FETCH_ASSOC);

        $imported = 0;
        foreach (array_chunk($rows, 500) as $chunk) {
            DB::transaction(function () use ($chunk, &$imported) {
                foreach ($chunk as $row) {
                    JobPosting::updateOrCreate(
                        ['id' => $row['id']],
                        [
                            'source' => $row['source'],
                            'company' => $row['company'],
                            'title' => $row['title'],
                            'location' => $row['location'],
                            'url' => $row['url'],
                            'posted_at' => $row['posted_at'],
                            'first_seen' => $row['first_seen'],
                            'salary' => $row['salary'],
                            'meta' => $row['meta'],
                        ]
                    );
                    $imported++;
                }
            });
        }

        $pruned = JobPosting::whereRaw('COALESCE(posted_at, first_seen) < ?', [now()->subDays(30)])
            ->delete();

        $total = JobPosting::count();
        $this->info("Imported {$imported} postings, pruned {$pruned} older than 30 days. Apply now holds {$total}.");

        return self::SUCCESS;
    }
}
