<?php

namespace App\Http\Controllers;

use App\Models\DigestSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
}
