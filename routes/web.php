<?php

use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DigestController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SettingsController;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Route;

// Public landing page. The hero visual is the real board: the five
// freshest postings, straight from the same store /jobs reads.
Route::get('/', function () {
    return view('welcome', [
        'previewJobs' => JobPosting::newestFirst()->limit(5)->get(),
        'totalJobs' => JobPosting::count(),
    ]);
})->name('home');

// Public job board (free tier capped at 3 results, enforced server-side).
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');

// Digest signup.
Route::post('/digest', [DigestController::class, 'store'])->name('digest.store');
Route::get('/digest/unsubscribe/{token}', [DigestController::class, 'unsubscribe'])->name('digest.unsubscribe');

// WorkOS AuthKit sign-in flow.
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::get('/auth/start', [AuthController::class, 'login'])->name('auth.start')->middleware('guest');
Route::get('/authenticate', [AuthController::class, 'callback'])->name('auth.callback')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Signed-in area. All routes here require a WorkOS-authenticated session.
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Digest signup backend (admin allowlist enforced in the controller).
    Route::get('/admin/subscribers', [SubscriberController::class, 'index'])->name('admin.subscribers');
    Route::post('/admin/subscribers/{subscriber}/unsubscribe', [SubscriberController::class, 'unsubscribe'])->name('admin.subscribers.unsubscribe');
    Route::delete('/admin/subscribers/{subscriber}', [SubscriberController::class, 'destroy'])->name('admin.subscribers.destroy');

    Route::get('/settings', [SettingsController::class, 'show'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::get('/billing', [BillingController::class, 'index'])->name('billing');
    Route::post('/billing/subscribe', [BillingController::class, 'subscribe'])->name('billing.subscribe');
    Route::post('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');
    Route::post('/billing/cancel', [BillingController::class, 'cancel'])->name('billing.cancel');

    Route::get('/checkout/success', [BillingController::class, 'checkoutSuccess'])->name('checkout.success');
    Route::get('/checkout/cancel', [BillingController::class, 'checkoutCancel'])->name('checkout.cancel');
});
