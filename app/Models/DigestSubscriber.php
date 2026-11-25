<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DigestSubscriber extends Model
{
    protected $fillable = [
        'email',
        'cadence',
        'unsubscribe_token',
        'subscribed_at',
        'unsubscribed_at',
    ];

    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (DigestSubscriber $subscriber) {
            $subscriber->unsubscribe_token ??= (string) Str::uuid();
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('unsubscribed_at');
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->unsubscribed_at === null;
    }
}
