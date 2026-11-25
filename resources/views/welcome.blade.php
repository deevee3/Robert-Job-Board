@extends('layouts.app')

@section('content')
<style>
    .bleed { width: 100vw; margin-left: calc(50% - 50vw); }
    .wrap { max-width: 1240px; margin: 0 auto; padding: 0 5%; }
    .dark { background: #2D312F; color: #FFFFFF; }
    .dark a { color: #FFFFFF; }
    .dark .btn-light { color: #2D2E2B; }
    .hero { margin-top: -28px; padding-top: 96px; padding-bottom: 72px; }
    .hero h1 { font-size: clamp(40px, 6vw, 68px); font-weight: 500; letter-spacing: -0.02em; line-height: 1.0; margin: 0 0 28px; max-width: 12ch; }
    .hero p.lead { font-size: 17px; line-height: 1.55; max-width: 52ch; margin: 0 0 36px; }
    .btn { display: inline-block; border-radius: 0; padding: 14px 26px; font-size: 15px; font-weight: 500; text-decoration: none; border: 0; cursor: pointer; }
    .btn-light { background: #FFFFFF; color: #2D2E2B; }
    .btn-dark { background: #2D2E2B; color: #FFFFFF; }
    .hero .alt { margin-left: 18px; font-size: 15px; text-decoration: underline; text-underline-offset: 3px; }
    .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; padding: 72px 0 80px; }
    .prod { border-top: 1px solid rgba(255,255,255,.85); padding-top: 18px; }
    .prod .head { display: flex; justify-content: space-between; align-items: baseline; gap: 16px; }
    .prod h2 { font-size: 30px; font-weight: 500; letter-spacing: -0.01em; margin: 0; }
    .prod .tag { font-size: 14px; text-align: right; }
    .prod p { font-size: 16px; line-height: 1.6; margin: 22px 0 26px; }
    .prod .foot { border-top: 1px solid rgba(255,255,255,.85); padding-top: 18px; display: flex; justify-content: space-between; align-items: center; }
    .prod .foot span { font-size: 15px; }
    .center-note { text-align: center; padding: 8px 0 88px; }
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
    .plan .price { font-size: 30px; font-weight: 500; margin: 10px 0 14px; }
    .plan .price small { font-size: 15px; color: #71726D; }
    .plan p { font-size: 15px; line-height: 1.6; margin: 0 0 26px; flex: 1; }
    .signoff { background: #FFFFFF; padding: 0 0 8px; margin-bottom: -48px; overflow: hidden; }
    .signoff .giant { font-size: clamp(64px, 13vw, 190px); font-weight: 500; letter-spacing: -0.03em; line-height: 0.95; color: #71726D; white-space: nowrap; }
    @media (max-width: 760px) {
        .wrap { padding: 0 20px; }
        .grid2, .sources, .plans { grid-template-columns: 1fr; }
        .grid2 { gap: 40px; padding: 40px 0 48px; }
        .hero { padding-top: 56px; padding-bottom: 48px; }
        .hero h1 { margin-bottom: 20px; }
        .hero p.lead { margin-bottom: 28px; }
        .prod .head { flex-direction: column; align-items: flex-start; gap: 4px; }
        .prod .tag { text-align: left; }
        .prod .foot { flex-wrap: wrap; gap: 12px; }
        .center-note { padding-bottom: 56px; }
        .band { padding: 48px 0 40px; }
        .pricing { padding-bottom: 56px; }
    }
</style>

<div class="bleed dark">
    <div class="wrap hero">
        <h1>Startup jobs. In their first 24 hours.</h1>
        <p class="lead">Robert watches the boards LinkedIn misses — a16z, YC, Hacker News, Cosign, the newsletters, and 141 company careers pages — and ranks every posting by how fresh it is. Apply while the pile is still small.</p>
        @auth
            <a class="btn btn-light" href="{{ route('jobs.index') }}">Browse jobs →</a>
        @else
            <a class="btn btn-light" href="{{ route('jobs.index') }}">Browse jobs →</a>
            <a class="alt" href="#pricing">See pricing</a>
        @endauth
    </div>

    <div class="wrap">
        <div class="grid2">
            <div class="prod">
                <div class="head">
                    <h2>The board</h2>
                    <span class="tag">Every posting, ranked by age.</span>
                </div>
                <p>Search and filter thousands of startup postings. Each one shows how old it is, where it came from, and links straight to the real application page.</p>
                <div class="foot">
                    <span>Free for 3 results</span>
                    <a class="btn btn-light" href="{{ route('jobs.index') }}">Browse jobs →</a>
                </div>
            </div>
            <div class="prod">
                <div class="head">
                    <h2>The digest</h2>
                    <span class="tag">The freshest 24 hours, in your inbox.</span>
                </div>
                <p>A weekday email with the newest postings first. No noise, and no week-old listings dressed up as new.</p>
                <div class="foot">
                    <span>Weekday mornings</span>
                    <a class="btn btn-light" href="{{ route('jobs.index') }}#digest">Get the digest →</a>
                </div>
            </div>
        </div>

        <div class="center-note">
            <p>Less noise, more signal. Robert checks the sources every weekday morning, so what you see is hours old. The early application is the one that gets read.</p>
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
