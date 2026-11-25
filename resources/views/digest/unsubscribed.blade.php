@extends('layouts.app')

@section('title', 'Unsubscribed')

@section('content')
    <div class="card">
        @if ($found)
            <h2>You're unsubscribed.</h2>
            <p>No more digest emails. The board is still here whenever you want it.</p>
        @else
            <h2>That link doesn't match a subscription.</h2>
            <p>It may have already been used, or the address was removed. Either way, nothing more will be sent to it.</p>
        @endif
        <p><a href="{{ route('jobs.index') }}">Browse jobs →</a></p>
    </div>
@endsection
