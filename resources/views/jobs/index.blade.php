@extends('layouts.app')

@section('title', 'Jobs')

@section('content')
<style>
    .board-head { margin: 8px 0 28px; }
    .board-head h1 { font-size: clamp(30px, 4vw, 44px); font-weight: 500; letter-spacing: -0.02em; line-height: 1.05; margin: 0 0 10px; }
    .board-head p { font-size: 16px; color: var(--muted); margin: 0; }
    .filters { display: flex; flex-wrap: wrap; gap: 10px; align-items: end; border-top: 1px solid var(--text); border-bottom: 1px solid var(--text); padding: 16px 0; margin-bottom: 8px; }
    .filters .field { display: flex; flex-direction: column; gap: 4px; }
    .filters label { font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase; letter-spacing: 0.04em; }
    .filters input[type=text], .filters select { font: inherit; font-size: 14px; border: 1px solid #d1d5db; border-radius: 0; padding: 8px 10px; background: #fff; }
    .filters .check { display: flex; align-items: center; gap: 6px; font-size: 14px; padding-bottom: 8px; }
    .filters .check label { text-transform: none; letter-spacing: 0; font-size: 14px; font-weight: 400; }
    .btn { display: inline-block; border-radius: 0; padding: 9px 18px; font-size: 14px; font-weight: 500; text-decoration: none; border: 0; cursor: pointer; background: var(--text); color: #fff; }
    .btn-light { background: #fff; color: var(--text); border: 1px solid var(--text); }
    .count-line { font-size: 14px; color: var(--muted); margin: 14px 0 4px; }
    .job { border-top: 1px solid #e5e7eb; padding: 16px 0; display: flex; justify-content: space-between; gap: 16px; align-items: baseline; }
    .job:last-of-type { border-bottom: 1px solid #e5e7eb; }
    .job .title { font-size: 17px; font-weight: 500; margin: 0 0 2px; }
    .job .title a { color: var(--text); text-decoration: none; }
    .job .title a:hover { text-decoration: underline; text-underline-offset: 3px; }
    .job .meta { font-size: 14px; color: var(--muted); margin: 0; }
    .job .age { font-size: 14px; white-space: nowrap; }
    .paywall { border: 1px solid var(--text); padding: 28px; margin: 28px 0; }
    .paywall h2 { font-size: 22px; font-weight: 500; margin: 0 0 8px; }
    .paywall p { font-size: 15px; line-height: 1.6; margin: 0 0 18px; }
    .paywall .row { display: flex; gap: 12px; flex-wrap: wrap; }
    .digest { border-top: 1px solid var(--text); margin-top: 40px; padding-top: 24px; }
    .digest h2 { font-size: 22px; font-weight: 500; margin: 0 0 6px; }
    .digest p { font-size: 15px; color: var(--muted); margin: 0 0 16px; }
    .digest form { display: flex; flex-wrap: wrap; gap: 10px; align-items: end; }
    .digest .field { display: flex; flex-direction: column; gap: 4px; }
    .digest label { font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase; letter-spacing: 0.04em; }
    .digest input[type=email], .digest select { font: inherit; font-size: 14px; border: 1px solid #d1d5db; border-radius: 0; padding: 8px 10px; background: #fff; }
    .empty { font-size: 15px; color: var(--muted); padding: 24px 0; }
    .pager { margin-top: 20px; font-size: 14px; }
</style>

<div class="board-head">
    <h1>Startup jobs, newest first.</h1>
    <p>Every posting shows how old it is and where it came from. Apply links go straight to the real application page.</p>
</div>

<form class="filters" method="GET" action="{{ route('jobs.index') }}">
    <div class="field">
        <label for="q">Search</label>
        <input type="text" id="q" name="q" value="{{ $filters['q'] }}" placeholder="Company or role">
    </div>
    <div class="field">
        <label for="source">Source</label>
        <select id="source" name="source">
            <option value="">All sources</option>
            @foreach ($sources as $key => $label)
                <option value="{{ $key }}" @selected($filters['source'] === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="field">
        <label for="age">Age</label>
        <select id="age" name="age">
            <option value="24h" @selected($filters['age'] === '24h')>Under 24 hours</option>
            <option value="7d" @selected($filters['age'] === '7d')>Under 7 days</option>
            <option value="all" @selected($filters['age'] === 'all')>All (30 days)</option>
        </select>
    </div>
    <div class="check">
        <input type="checkbox" id="remote" name="remote" value="1" @checked($filters['remote'])>
        <label for="remote">Remote only</label>
    </div>
    <button type="submit" class="btn">Filter →</button>
</form>

<p class="count-line">{{ number_format($total) }} {{ $total === 1 ? 'posting' : 'postings' }} match{{ $paywalled ? ' — showing the first 3' : '' }}.</p>

@forelse ($jobs as $job)
    <div class="job">
        <div>
            <p class="title">
                @if ($job->url)
                    <a href="{{ $job->url }}" target="_blank" rel="noopener">{{ $job->title ?: 'Untitled role' }} →</a>
                @else
                    {{ $job->title ?: 'Untitled role' }}
                @endif
            </p>
            <p class="meta">
                {{ $job->company ?: 'Company not listed' }}
                @if ($job->location) · {{ $job->location }} @endif
                @if ($job->salary) · {{ $job->salary }} @endif
                · via {{ $job->source_label }}
            </p>
        </div>
        <div class="age">{{ $job->age_label }}</div>
    </div>
@empty
    <p class="empty">Nothing matches that yet. Loosen the filters, or check back after the next weekday run.</p>
@endforelse

@if ($paywalled)
    <div class="paywall">
        <h2>That's the free view.</h2>
        <p>Free shows 3 results per search. Basic unlocks the full board — every match, all filters, the weekday digest — for $7 a month.</p>
        <div class="row">
            @auth
                <a class="btn" href="{{ route('billing') }}">Go Basic — $7/month →</a>
            @else
                <a class="btn" href="{{ route('login') }}">Sign in to unlock →</a>
                <a class="btn btn-light" href="{{ route('home') }}#pricing">See plans</a>
            @endauth
        </div>
    </div>
@endif

@if ($subscribed && method_exists($jobs, 'links'))
    <div class="pager">{{ $jobs->links() }}</div>
@endif

<div class="digest" id="digest">
    <h2>The digest</h2>
    <p>The freshest postings, in your inbox. Newest first, same ranking as the board.</p>
    <form method="POST" action="{{ route('digest.store') }}">
        @csrf
        <div class="field">
            <label for="digest-email">Email</label>
            <input type="email" id="digest-email" name="email" required placeholder="you@example.com">
        </div>
        <div class="field">
            <label for="digest-cadence">Cadence</label>
            <select id="digest-cadence" name="cadence">
                <option value="daily">Weekday mornings</option>
                <option value="weekly">Weekly</option>
            </select>
        </div>
        <button type="submit" class="btn">Get the digest →</button>
    </form>
</div>
@endsection
