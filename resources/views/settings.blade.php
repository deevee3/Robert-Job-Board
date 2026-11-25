@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="card">
        <h2>Account settings</h2>
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf
            @method('PUT')

            <label for="name">Display name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}" readonly>
            <p class="muted">Email is managed by WorkOS and cannot be changed here.</p>

            <div class="row" style="margin-top: 16px;">
                <button type="submit" class="button">Save changes</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2>Sign out</h2>
        <p class="muted">Signing out ends this browser session and clears the WorkOS session cookie.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="button secondary">Sign out</button>
        </form>
    </div>
@endsection
