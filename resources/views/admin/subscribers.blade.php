@extends('layouts.app')

@section('title', 'Digest subscribers')

@section('content')
    <div class="card">
        <h2>Digest subscribers</h2>
        <p class="muted">
            {{ $counts['active'] }} active · {{ $counts['daily'] }} weekday · {{ $counts['weekly'] }} weekly · {{ $counts['unsubscribed'] }} unsubscribed
        </p>
    </div>

    <div class="card">
        @if ($subscribers->isEmpty())
            <p>No signups yet. The form lives at the bottom of the <a href="{{ route('jobs.index') }}#digest">jobs board</a>.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="text-align: left; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 8px 6px;">Email</th>
                        <th style="padding: 8px 6px;">Cadence</th>
                        <th style="padding: 8px 6px;">Signed up</th>
                        <th style="padding: 8px 6px;">Status</th>
                        <th style="padding: 8px 6px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subscribers as $subscriber)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px 6px;">{{ $subscriber->email }}</td>
                            <td style="padding: 8px 6px;">{{ $subscriber->cadence === 'daily' ? 'Weekday' : 'Weekly' }}</td>
                            <td style="padding: 8px 6px;">{{ $subscriber->subscribed_at?->toDateString() }}</td>
                            <td style="padding: 8px 6px;">{{ $subscriber->is_active ? 'Active' : 'Unsubscribed' }}</td>
                            <td style="padding: 8px 6px; white-space: nowrap;">
                                @if ($subscriber->is_active)
                                    <form class="inline" method="POST" action="{{ route('admin.subscribers.unsubscribe', $subscriber) }}">
                                        @csrf
                                        <button type="submit" class="button secondary">Unsubscribe</button>
                                    </form>
                                @endif
                                <form class="inline" method="POST" action="{{ route('admin.subscribers.destroy', $subscriber) }}" onsubmit="return confirm('Remove {{ $subscriber->email }} for good?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top: 16px;">{{ $subscribers->links() }}</div>
        @endif
    </div>
@endsection
