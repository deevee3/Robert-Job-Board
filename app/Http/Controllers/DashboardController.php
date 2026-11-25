<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Signed-in home. Shows the account and subscription summary.
     * Factory apps add their own sections here.
     */
    public function index(): View
    {
        $user = Auth::user();

        return view('dashboard', [
            'user' => $user,
            'subscription' => $user->subscription('default'),
        ]);
    }
}
