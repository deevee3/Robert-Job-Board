@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="card">
        <h2>Welcome back, {{ $user->name }}</h2>
        <p class="muted">{{ $user->email }}</p>
    </div>

    <div class="card">
        <h2>Account</h2>
        <p>Signed in through WorkOS. Member since {{ $user->created_at->toDateString() }}.</p>
        <p><a href="{{ route('settings') }}">Account settings</a></p>
    </div>

    <div class="card">
        <h2>Subscription</h2>
        @if ($subscription)
            <p>Status: <strong>{{ $subscription->stripe_status }}</strong></p>
            @if ($subscription->onTrial())
                <p>Trial ends {{ $subscription->trial_ends_at?->toDateString() }}.</p>
            @elseif ($subscription->canceled())
                <p>Cancelled — access continues until {{ $subscription->ends_at?->toDateString() }}.</p>
            @else
                <p>Active — renews automatically.</p>
            @endif
        @else
            <p>No subscription yet.</p>
        @endif
        <p><a href="{{ route('billing') }}">Billing</a></p>
    </div>

    <div class="card">
        <h2>The board</h2>
        <p>Every posting, ranked by age. Search, filter, and apply straight from the listing.</p>
        <p><a href="{{ route('jobs.index') }}">Browse jobs →</a></p>
    </div>
@endsection
