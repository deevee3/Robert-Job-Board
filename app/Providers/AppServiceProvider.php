<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * Factory apps add their own bindings, view composers, and event
     * listeners here. Cashier's Stripe webhook is handled by Cashier
     * itself at POST /stripe/webhook; custom handling belongs on the
     * `WebhookReceived` event Cashier dispatches for every payload.
     */
    public function boot(): void
    {
        //
    }
}
