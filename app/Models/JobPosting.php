<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    protected $table = 'job_postings';

    protected $fillable = [
        'id',
        'source',
        'company',
        'title',
        'location',
        'url',
        'posted_at',
        'first_seen',
        'salary',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'posted_at' => 'datetime',
            'first_seen' => 'datetime',
        ];
    }

    /**
     * Human labels for the scraper's source keys.
     */
    public static function sourceLabels(): array
    {
        return [
            'careers' => 'Careers pages',
            'a16z' => 'a16z board',
            'yc' => 'YC board',
            'lennys' => "Lenny's Jobs",
            'hn' => "HN Who's Hiring",
            'bruntwork' => 'BruntWork',
            'cosign' => 'Cosign',
            'freelancer' => 'Freelancer',
        ];
    }

    public function getSourceLabelAttribute(): string
    {
        return self::sourceLabels()[$this->source] ?? ucfirst((string) $this->source);
    }

    /**
     * Freshness anchor: when the posting went up, falling back to when
     * the scraper first saw it.
     */
    public function getFreshAtAttribute()
    {
        return $this->posted_at ?? $this->first_seen;
    }

    public function getAgeLabelAttribute(): string
    {
        $at = $this->fresh_at;
        if (! $at) {
            return 'age unknown';
        }

        $hours = (int) $at->diffInHours(now());
        if ($hours < 1) {
            return 'under 1h old';
        }
        if ($hours < 24) {
            return $hours.'h old';
        }

        return intdiv($hours, 24).'d old';
    }

    public function scopeNewestFirst(Builder $query): Builder
    {
        return $query->orderByRaw('COALESCE(posted_at, first_seen) DESC');
    }
}
