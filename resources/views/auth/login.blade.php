@extends('layouts.app')

@section('title', 'Sign in')

@section('content')
    <div class="card">
        <h2>Sign in to {{ config('theme.name') }}</h2>
        <p class="muted">Sign-in is handled by WorkOS AuthKit — email, social login, magic links, passkeys, and SSO. There is no password stored here.</p>
        <div class="row" style="margin-top: 16px;">
            <a class="button" href="{{ route('auth.start') }}">Continue with WorkOS</a>
            <a class="button secondary" href="{{ route('home') }}">Back to home</a>
        </div>
    </div>
@endsection
