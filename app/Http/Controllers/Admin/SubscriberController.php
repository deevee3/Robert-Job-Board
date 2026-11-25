<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigestSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriberController extends Controller
{
    /**
     * Digest signup backend: who signed up, on what cadence, and
     * whether they're still active. Gated to the ADMIN_EMAILS allowlist
     * on top of sign-in — subscriber emails are not public data.
     */
    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        return view('admin.subscribers', [
            'subscribers' => DigestSubscriber::orderByDesc('subscribed_at')->paginate(50),
            'counts' => [
                'active' => DigestSubscriber::active()->count(),
                'daily' => DigestSubscriber::active()->where('cadence', 'daily')->count(),
                'weekly' => DigestSubscriber::active()->where('cadence', 'weekly')->count(),
                'unsubscribed' => DigestSubscriber::whereNotNull('unsubscribed_at')->count(),
            ],
        ]);
    }

    public function unsubscribe(Request $request, DigestSubscriber $subscriber): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $subscriber->update(['unsubscribed_at' => now()]);

        return redirect()->route('admin.subscribers')
            ->with('status', $subscriber->email.' unsubscribed.');
    }

    public function destroy(Request $request, DigestSubscriber $subscriber): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $email = $subscriber->email;
        $subscriber->delete();

        return redirect()->route('admin.subscribers')
            ->with('status', $email.' removed.');
    }

    protected function authorizeAdmin(Request $request): void
    {
        $allowed = array_filter(array_map(
            'trim',
            explode(',', (string) env('ADMIN_EMAILS', 'dvaughnhouse@gmail.com,dvaughn@dvaughnhouse.com'))
        ));

        abort_unless(
            $request->user() && in_array($request->user()->email, $allowed, true),
            403
        );
    }
}
