<?php

namespace App\Http\Controllers;

use App\Models\DigestSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DigestController extends Controller
{
    /**
     * Digest signup: store the email and cadence. Sending runs on the
     * same freshness ranking as the board.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'cadence' => ['required', 'string', 'in:daily,weekly'],
        ]);

        DigestSubscriber::updateOrCreate(
            ['email' => $validated['email']],
            [
                'cadence' => $validated['cadence'],
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]
        );

        return redirect()->route('jobs.index')
            ->with('status', "You're on the list. The freshest postings land in your inbox weekday mornings.");
    }

    /**
     * One-click unsubscribe from a digest email link.
     */
    public function unsubscribe(string $token): View
    {
        $subscriber = DigestSubscriber::where('unsubscribe_token', $token)->first();

        if ($subscriber && $subscriber->is_active) {
            $subscriber->update(['unsubscribed_at' => now()]);
        }

        return view('digest.unsubscribed', [
            'found' => (bool) $subscriber,
        ]);
    }
}
