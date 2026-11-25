<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use WorkOS\UserManagement;
use WorkOS\WorkOS;

class AuthController extends Controller
{
    /**
     * Show the sign-in page. The only sign-in path is WorkOS AuthKit.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Start the WorkOS AuthKit sign-in flow.
     *
     * workos-php 4.x has no `new WorkOS($apiKey)` constructor and no
     * `->userManagement` property: WorkOS is a static config holder and
     * UserManagement is instantiated directly. (Verified against the
     * installed workos-php 4.32.0 in vendor/.)
     */
    public function login(): RedirectResponse
    {
        WorkOS::setApiKey(config('workos.api_key'));
        WorkOS::setClientId(config('workos.client_id'));

        $userManagement = new UserManagement();

        $url = $userManagement->getAuthorizationUrl(
            config('workos.redirect_url'),
            null, // state
            'authkit',
        );

        return redirect($url);
    }

    /**
     * WorkOS redirects back here after sign-in. Exchange the code for a
     * user, create or update the local user record, and start a Laravel
     * session. The session is Laravel's own encrypted session cookie;
     * the kit stores no WorkOS access/refresh tokens and uses no
     * WorkOS sealed session.
     */
    public function callback(Request $request): RedirectResponse
    {
        $code = $request->query('code');

        if (! $code) {
            return redirect()->route('login')
                ->with('error', 'Sign-in was not completed. Please try again.');
        }

        WorkOS::setApiKey(config('workos.api_key'));
        WorkOS::setClientId(config('workos.client_id'));

        $userManagement = new UserManagement();

        $authResponse = $userManagement->authenticateWithCode(
            config('workos.client_id'),
            $code,
        );

        $workosUser = $authResponse->user;

        $name = trim(($workosUser->firstName ?? '').' '.($workosUser->lastName ?? ''));

        $user = User::updateOrCreate(
            ['workos_id' => $workosUser->id],
            [
                'name' => $name !== '' ? $name : $workosUser->email,
                'email' => $workosUser->email,
            ],
        );

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Sign out locally. The kit stores no WorkOS session, so there is
     * no WorkOS session to revoke here. Apps that keep WorkOS tokens
     * can add a WorkOS-side logout via UserManagement::getLogoutUrl().
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
