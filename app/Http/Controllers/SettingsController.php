<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Account settings. Email is managed by WorkOS and shown read-only.
     */
    public function show(): View
    {
        return view('settings', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the display name. Nothing else is editable here — email and
     * password live in WorkOS.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->update($validated);

        return redirect()->route('settings')
            ->with('status', 'Account updated.');
    }
}
