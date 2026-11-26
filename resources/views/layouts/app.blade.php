<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('theme.name') }} @hasSection('title') — @yield('title') @endif</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <style>
        @font-face { font-family: 'Liberation Sans'; src: url('/fonts/LiberationSans-Regular.ttf') format('truetype'); font-weight: 400; font-style: normal; font-display: swap; }
        @font-face { font-family: 'Liberation Sans'; src: url('/fonts/LiberationSans-Bold.ttf') format('truetype'); font-weight: 700; font-style: normal; font-display: swap; }
        :root {
            --primary: {{ config('theme.colors.primary') }};
            --accent: {{ config('theme.colors.accent') }};
            --background: {{ config('theme.colors.background') }};
            --surface: {{ config('theme.colors.surface') }};
            --text: {{ config('theme.colors.text') }};
            --muted: {{ config('theme.colors.muted') }};
            --font-sans: {{ config('theme.fonts.sans') }};
            --font-display: {{ config('theme.fonts.display') }};
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: var(--font-sans); background: var(--background); color: var(--text); line-height: 1.5; }
        a { color: var(--primary); }
        header { border-bottom: 1px solid #e5e7eb; background: var(--surface); }
        .header-inner { max-width: 880px; margin: 0 auto; padding: 14px 20px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
        .brand { display: flex; align-items: center; gap: 10px; text-decoration: none; color: var(--text); font-family: var(--font-display); font-weight: 700; font-size: 18px; margin-right: auto; }
        .brand img { height: 24px; width: auto; }
        nav { display: flex; align-items: center; gap: 14px; font-size: 14px; }
        nav a { text-decoration: none; color: var(--text); padding: 12px 4px; }
        nav a:hover { color: var(--primary); }
        nav a:focus-visible, .brand:focus-visible { outline: 2px solid var(--text); outline-offset: 3px; }
        ::selection { background: var(--text); color: var(--surface); }
        main { max-width: 880px; margin: 0 auto; padding: 28px 20px 48px; }
        footer { max-width: 880px; margin: 0 auto; padding: 0 20px 28px; color: var(--muted); font-size: 13px; }
        .card { background: var(--surface); border: 1px solid #e5e7eb; border-radius: 10px; padding: 18px 20px; margin-bottom: 18px; }
        .card h2 { margin: 0 0 8px; font-size: 18px; font-family: var(--font-display); }
        .card p { margin: 6px 0; }
        .muted { color: var(--muted); font-size: 14px; }
        .button { display: inline-block; background: var(--primary); color: #fff; border: 0; border-radius: 8px; padding: 10px 16px; font-size: 14px; cursor: pointer; text-decoration: none; }
        .button.secondary { background: var(--surface); color: var(--text); border: 1px solid #d1d5db; }
        .button.danger { background: #b91c1c; }
        .button:disabled { opacity: .55; cursor: not-allowed; }
        .flash { border-radius: 8px; padding: 10px 14px; margin-bottom: 18px; font-size: 14px; }
        .flash.status { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .flash.error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        label { display: block; font-size: 13px; font-weight: 600; margin: 12px 0 4px; }
        input[type=text], input[type=email] { width: 100%; max-width: 420px; font: inherit; font-size: 14px; border: 1px solid #d1d5db; border-radius: 8px; padding: 9px 11px; }
        input[readonly] { background: var(--surface); color: var(--muted); }
        .row { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        form.inline { display: inline; }
    </style>
</head>
<body>
<header>
    <div class="header-inner">
        <a class="brand" href="{{ route('home') }}">
            <img src="{{ config('theme.logo') }}" alt="{{ config('theme.name') }}">
        </a>
        <nav>
            <a href="{{ route('jobs.index') }}">Jobs</a>
            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('settings') }}">Settings</a>
                <a href="{{ route('billing') }}">Billing</a>
                <form class="inline" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="button secondary">Sign out</button>
                </form>
            @else
                <a href="{{ route('login') }}">Sign in</a>
            @endauth
        </nav>
    </div>
</header>

<main>
    @if (session('status'))
        <div class="flash status">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="flash error">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="flash error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @yield('content')
</main>

<footer>
    {{ config('theme.name') }}, a project of Glenride.
</footer>
</body>
</html>
