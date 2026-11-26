<div style="font-family: -apple-system, 'Helvetica Neue', Arial, sans-serif; color: #2D2E2B; max-width: 640px; margin: 0 auto; padding: 24px;">
    <h1 style="font-size: 24px; font-weight: 500; letter-spacing: -0.02em; margin: 0 0 6px;">Fresh startup jobs</h1>
    <p style="font-size: 14px; color: #71726D; margin: 0 0 24px;">Newest first, from the boards the big sites miss.</p>

    @foreach ($jobs as $job)
        <div style="border-top: 1px solid #e5e7eb; padding: 12px 0;">
            <p style="font-size: 16px; font-weight: 500; margin: 0 0 2px;">
                @if ($job->url)
                    <a href="{{ $job->url }}" style="color: #2D2E2B;">{{ $job->title ?: 'Untitled role' }} →</a>
                @else
                    {{ $job->title ?: 'Untitled role' }}
                @endif
            </p>
            <p style="font-size: 13px; color: #71726D; margin: 0;">
                {{ $job->company ?: 'Company not listed' }}
                @if ($job->location) · {{ $job->location }} @endif
                · {{ $job->age_label }} · via {{ $job->source_label }}
            </p>
        </div>
    @endforeach

    <p style="font-size: 13px; color: #71726D; margin-top: 28px;">
        Apply, a project of Glenride ·
        <a href="{{ route('digest.unsubscribe', $subscriber->unsubscribe_token) }}" style="color: #71726D;">Unsubscribe</a>
    </p>
</div>
