@extends('layouts.app')

@section('content')
<style>
    .bleed { width: 100vw; margin-left: calc(50% - 50vw); }
    .wrap { max-width: 1240px; margin: 0 auto; padding: 0 5%; }
    .dark { background: #2D312F; color: #FFFFFF; }
    .dark a { color: #FFFFFF; }
    .dark .btn-light { color: #2D2E2B; }
    .dark ::selection { background: #FFFFFF; color: #2D2E2B; }
    .hero { margin-top: -28px; padding-top: 104px; padding-bottom: 88px; }
    .hero h1 { font-size: clamp(44px, 7vw, 76px); font-weight: 400; letter-spacing: -0.03em; line-height: 1.02; margin: 0; max-width: 13ch; text-wrap: balance; }
    .rule { border-top: 1px solid rgba(255,255,255,.85); }
    .product { padding: 56px 0 8px; }
    .prod-name { font-size: 30px; font-weight: 400; letter-spacing: -0.01em; margin: 0 0 10px; }
    .prod-line { font-size: 17px; line-height: 1.5; margin: 0 0 32px; max-width: 52ch; }
    .prod-copy { font-size: 16px; line-height: 1.6; margin: 0 0 28px; max-width: 58ch; }
    .board-panel { background: #FFFFFF; color: #2D2E2B; max-width: 880px; }
    .board-panel a { color: #2D2E2B; }
    .bp-main { min-width: 0; }
    .board-panel ::selection { background: #2D2E2B; color: #FFFFFF; }
    .bp-row { display: flex; justify-content: space-between; align-items: baseline; gap: 20px; padding: 18px 22px; border-bottom: 1px solid #E4E4E0; text-decoration: none; color: #2D2E2B; }
    .bp-row:last-of-type { border-bottom: 0; }
    .bp-title { font-size: 17px; font-weight: 500; letter-spacing: -0.01em; margin: 0 0 3px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .bp-meta { font-size: 14px; color: #71726D; margin: 0; }
    .bp-age { font-size: 14px; white-space: nowrap; font-variant-numeric: tabular-nums; }
    .bp-foot { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 16px 22px; border-top: 1px solid #2D2E2B; font-size: 14px; }
    .bp-foot .count { font-variant-numeric: tabular-nums; }
    .bp-foot a { color: #2D2E2B; font-weight: 500; text-decoration: none; }
    .bp-empty { padding: 22px; font-size: 15px; color: #71726D; margin: 0; }
    .cta-row { display: flex; align-items: center; flex-wrap: wrap; gap: 18px; margin-top: 32px; }
    .btn { display: inline-block; border-radius: 0; padding: 14px 26px; font-size: 15px; font-weight: 500; text-decoration: none; border: 0; cursor: pointer; }
    .btn-light { background: #FFFFFF; color: #2D2E2B; }
    .btn-dark { background: #2D2E2B; color: #FFFFFF; }
    .alt { font-size: 15px; text-decoration: underline; text-underline-offset: 3px; }
    .center-note { text-align: center; padding: 64px 0 88px; }
    .center-note p { font-size: 16px; line-height: 1.6; max-width: 58ch; margin: 0 auto; }
    .band { background: #FFFFFF; color: #2D2E2B; padding: 72px 0 64px; }
    .band h3 { font-size: 15px; font-weight: 500; margin: 0 0 36px; }
    .sources { display: grid; grid-template-columns: repeat(3, 1fr); gap: 36px 48px; }
    .src { border-top: 1px solid #2D2E2B; padding-top: 14px; }
    .src h4 { font-size: 17px; font-weight: 500; margin: 0 0 6px; }
    .src p { font-size: 14px; color: #71726D; margin: 0; line-height: 1.5; }
    .pricing { background: #FFFFFF; color: #2D2E2B; padding: 24px 0 80px; }
    .plans { display: grid; grid-template-columns: repeat(3, 1fr); gap: 48px; }
    .plan { border-top: 1px solid #2D2E2B; padding-top: 18px; display: flex; flex-direction: column; }
    .plan h4 { font-size: 20px; font-weight: 500; margin: 0 0 4px; }
    .plan .price { font-size: 30px; font-weight: 500; margin: 10px 0 14px; font-variant-numeric: tabular-nums; }
    .plan .price small { font-size: 15px; color: #71726D; }
    .plan p { font-size: 15px; line-height: 1.6; margin: 0 0 26px; flex: 1; }
    .plan .btn { align-self: flex-start; }
    .signoff { background: #FFFFFF; padding: 0 0 8px; margin-bottom: -48px; overflow: hidden; }
    .signoff .giant { font-size: clamp(64px, 13vw, 190px); font-weight: 500; letter-spacing: -0.03em; line-height: 0.95; color: #71726D; white-space: nowrap; }
    a:focus-visible, .btn:focus-visible { outline: 2px solid currentColor; outline-offset: 3px; }
    @media (prefers-reduced-motion: no-preference) {
        .btn, .bp-row, .bp-foot a, .alt { transition: background-color .2s ease-out, color .2s ease-out; }
        .btn-light:hover { background: #E9EBE2; }
        .btn-dark:hover { background: #45473F; }
        .bp-row:hover { background: #F6F6F2; }
        .bp-foot a:hover, .alt:hover { text-decoration: underline; text-underline-offset: 3px; }
    }
    @media (max-width: 760px) {
        .wrap { padding: 0 20px; }
        .sources, .plans { grid-template-columns: 1fr; }
        .hero { padding-top: 64px; padding-bottom: 56px; }
        .product { padding-top: 40px; }
        .prod-line { margin-bottom: 24px; }
        .bp-row { flex-direction: column; gap: 2px; padding: 16px 18px; }
        .bp-foot { padding: 14px 18px; }
        .center-note { padding: 48px 0 64px; }
        .band { padding: 48px 0 40px; }
        .pricing { padding-bottom: 56px; }
    }
</style>

<div class="bleed dark">
    <div class="wrap hero">
        <h1>Startup jobs. In their first 24 hours.</h1>
    </div>

    <div class="wrap">
        <div class="rule"></div>

        <section class="product">
            <h2 class="prod-name">The board</h2>
            <p class="prod-line">Every posting, ranked by age. Apply while the pile is still small.</p>

            <div class="board-panel">
                @forelse ($previewJobs as $job)
                    <a class="bp-row" href="{{ route('jobs.index') }}">
                        <span class="bp-main">
                            <span class="bp-title">{{ $job->title ?: 'Untitled role' }}</span>
                            <span class="bp-meta" style="display:block;">{{ $job->company ?: 'Company not listed' }}@if ($job->location) · {{ $job->location }}@endif · via {{ $job->source_label }}</span>
                        </span>
                        <span class="bp-age">{{ $job->age_label }}</span>
                    </a>
                @empty
                    <p class="bp-empty">The board refreshes every weekday morning. Check back after the next run.</p>
                @endforelse
                <div class="bp-foot">
                    <span class="count">{{ number_format($totalJobs) }} postings on the board</span>
                    <a href="{{ route('jobs.index') }}">Browse jobs →</a>
                </div>
            </div>

            <div class="cta-row">
                <a class="btn btn-light" href="{{ route('jobs.index') }}">Browse jobs →</a>
                <a class="alt" href="#pricing">See pricing</a>
            </div>
        </section>

        <section class="product">
            <h2 class="prod-name">The digest</h2>
            <p class="prod-line">The freshest 24 hours, in your inbox.</p>
            <p class="prod-copy">A weekday email with the newest postings first. No noise, and no week-old listings dressed up as new.</p>
            <div class="cta-row" style="margin-top: 0;">
                <a class="btn btn-light" href="{{ route('jobs.index') }}#digest">Get the digest →</a>
                <span style="font-size: 15px;">Weekday mornings</span>
            </div>
        </section>

        <div class="center-note">
            <p>Less noise, more signal. Apply checks the sources every weekday morning, so what you see is hours old. The early application is the one that gets read.</p>
        </div>
    </div>
</div>

<div class="bleed band">
    <div class="wrap">
        <h3>Where the jobs come from</h3>
        <div class="sources">
            <div class="src"><h4>a16z board</h4><p>Portfolio companies hiring now.</p></div>
            <div class="src"><h4>YC board</h4><p>Batch companies, every stage.</p></div>
            <div class="src"><h4>HN Who's Hiring</h4><p>The monthly thread, parsed.</p></div>
            <div class="src"><h4>Cosign</h4><p>Startups and their backers.</p></div>
            <div class="src"><h4>Newsletters</h4><p>Next Play, a16z Build, Founders You Should Know.</p></div>
            <div class="src"><h4>Careers pages</h4><p>141 companies, checked direct.</p></div>
        </div>
    </div>
</div>

<div class="bleed pricing" id="pricing">
    <div class="wrap">
        <div class="plans">
            <div class="plan">
                <h4>Free</h4>
                <div class="price">$0</div>
                <p>3 results per search. Enough to see how fresh the board runs.</p>
                <a class="btn btn-dark" href="{{ route('login') }}">Start free →</a>
            </div>
            <div class="plan">
                <h4>Basic</h4>
                <div class="price">$7 <small>/ month</small></div>
                <p>Full results, filters, and the weekday digest.</p>
                <a class="btn btn-dark" href="{{ route('login') }}">Go Basic →</a>
            </div>
            <div class="plan">
                <h4>Unlimited</h4>
                <div class="price">$47 <small>/ month</small></div>
                <p>Everything, no caps. Alerts and saved searches as they ship.</p>
                <a class="btn btn-dark" href="{{ route('login') }}">Go Unlimited →</a>
            </div>
        </div>
    </div>
</div>

<div class="bleed signoff">
    <div class="wrap">
        <div class="giant">apply early</div>
    </div>
</div>
@endsection
