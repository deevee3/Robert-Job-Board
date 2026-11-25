<?php

namespace App\Console\Commands;

use App\Mail\DigestMail;
use App\Models\DigestSubscriber;
use App\Models\JobPosting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDigest extends Command
{
    protected $signature = 'digest:send {--cadence=daily : daily or weekly} {--limit=20 : max postings per digest}';

    protected $description = 'Send the freshness-ranked job digest to active subscribers';

    public function handle(): int
    {
        $cadence = $this->option('cadence');
        if (! in_array($cadence, ['daily', 'weekly'], true)) {
            $this->error("Cadence must be daily or weekly, got {$cadence}");

            return self::FAILURE;
        }

        $window = $cadence === 'daily' ? now()->subDay() : now()->subDays(7);
        $jobs = JobPosting::query()
            ->whereRaw('COALESCE(posted_at, first_seen) >= ?', [$window])
            ->newestFirst()
            ->limit((int) $this->option('limit'))
            ->get();

        if ($jobs->isEmpty()) {
            $this->info('No fresh postings in the window; nothing sent.');

            return self::SUCCESS;
        }

        $subscribers = DigestSubscriber::active()->where('cadence', $cadence)->get();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new DigestMail($subscriber, $jobs));
        }

        $this->info("Sent {$cadence} digest ({$jobs->count()} postings) to {$subscribers->count()} subscribers.");

        return self::SUCCESS;
    }
}
