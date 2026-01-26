<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * Controller for handling user logins and creations
 */
class LoginController extends Controller
{
    /**
     * Constructor class for applying middleware
     */
    public function __construct()
    {
        $this->middleware('guest')->only(['login', 'callback']);
        $this->middleware('auth')->only('logout');
    }

    /**
     * Callback for OAuth application to handle processing of logins
     */
    public function callback(): RedirectResponse
    {
        $socialiteUser = Socialite::driver('mediawiki')->user();

        $user = User::findOrCreate($socialiteUser->name, true);

        if (count($user->events) === 0) {
            $user->newEvent('created-login');
        }

        abort_if($user->hasFlag('login-disabled'), 403, __('login-disabled'));

        Auth::login($user);

        return redirect()->intended();
    }

    /**
     * Handles login web requests to forward to OAuth
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function login()
    {
        return Socialite::driver('mediawiki')->redirect();
    }

    /**
     * Handles a logout
     *
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();

        return redirect('/');
    }

    /**
     * Guards the application for logins
     *
     * @return Guard|StatefulGuard
     */
    private function guard()
    {
        return Auth::guard();
    }
}
