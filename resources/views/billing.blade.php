@extends('layouts.app')

@section('title', 'Billing')

@section('content')
    <div class="card">
        <h2>Billing</h2>

        @if (! $billingConfigured)
            <p><strong>Billing isn't open yet.</strong></p>
            <p class="muted">Checkout opens once payment is configured. Until then, the free board is yours — 3 results per search.</p>
        @elseif ($subscription)
            <p>Status: <strong>{{ $subscription->stripe_status }}</strong></p>
            @if ($subscription->onTrial())
                <p>Trial ends {{ $subscription->trial_ends_at?->toDateString() }}.</p>
            @elseif ($subscription->canceled())
                <p>Cancelled — access continues until {{ $subscription->ends_at?->toDateString() }}.</p>
            @else
                <p>Active — renews automatically.</p>
            @endif

            <div class="row" style="margin-top: 16px;">
                <form method="POST" action="{{ route('billing.portal') }}">
                    @csrf
                    <button type="submit" class="button">Manage billing</button>
                </form>
                @if (! $subscription->canceled())
                    <form method="POST" action="{{ route('billing.cancel') }}" onsubmit="return confirm('Cancel at the end of the billing period?');">
                        @csrf
                        <button type="submit" class="button danger">Cancel subscription</button>
                    </form>
                @endif
            </div>
        @else
            <p>No subscription yet. Choose a plan to start.</p>
            <div class="row" style="margin-top: 16px;">
                <form method="POST" action="{{ route('billing.subscribe') }}">
                    @csrf
                    <input type="hidden" name="plan" value="basic">
                    <button type="submit" class="button">Basic — $7/month</button>
                </form>
                <form method="POST" action="{{ route('billing.subscribe') }}">
                    @csrf
                    <input type="hidden" name="plan" value="unlimited">
                    <button type="submit" class="button">Unlimited — $47/month</button>
                </form>
            </div>
            <p class="muted">Checkout runs through Stripe. Basic unlocks the full board; Unlimited removes every cap.</p>
        @endif
    </div>
@endsection
