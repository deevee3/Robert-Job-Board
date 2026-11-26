<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BillingController extends Controller
{
    /**
     * Billing page: subscription status, plan actions, portal link.
     * When Stripe keys are still placeholders, the page says so instead
     * of showing broken checkout buttons.
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('billing', [
            'user' => $user,
            'subscription' => $user->subscription('default'),
            'billingConfigured' => $this->billingConfigured(),
        ]);
    }

    /**
     * Start a subscription through Stripe Checkout.
     * Apply plans: Basic ($7/month) and Unlimited ($47/month).
     */
    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan' => ['required', 'string', 'in:basic,unlimited'],
        ]);

        $priceId = $validated['plan'] === 'unlimited'
            ? config('services.stripe.price_unlimited')
            : config('services.stripe.price_basic');

        return $request->user()
            ->newSubscription('default', $priceId)
            ->checkout([
                'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
            ]);
    }

    /**
     * Send the user to the Stripe billing portal to manage payment
     * methods, invoices, and plan changes.
     */
    public function portal(Request $request): RedirectResponse
    {
        return $request->user()->redirectToBillingPortal(route('billing'));
    }

    /**
     * Cancel at the end of the current billing period. Access continues
     * until the period ends (Cashier's default cancel behaviour).
     */
    public function cancel(Request $request): RedirectResponse
    {
        $subscription = $request->user()->subscription('default');

        if ($subscription) {
            $subscription->cancel();
        }

        return redirect()->route('billing')
            ->with('status', 'Subscription cancelled. Access continues until the end of the billing period.');
    }

    /**
     * Stripe Checkout returns here after a successful payment.
     */
    public function checkoutSuccess(): RedirectResponse
    {
        return redirect()->route('billing')
            ->with('status', 'Payment complete. Your subscription is active.');
    }

    /**
     * Stripe Checkout returns here when the user backs out.
     */
    public function checkoutCancel(): RedirectResponse
    {
        return redirect()->route('billing')
            ->with('status', 'Checkout cancelled. No charge was made.');
    }

    /**
     * Billing is configured only when real Stripe keys are set. The
     * placeholders in .env.example must be replaced first.
     */
    protected function billingConfigured(): bool
    {
        $key = (string) config('services.stripe.key');
        $secret = (string) config('services.stripe.secret');

        return $key !== ''
            && $secret !== ''
            && ! str_contains($key, 'your_')
            && ! str_contains($secret, 'your_');
    }
}
