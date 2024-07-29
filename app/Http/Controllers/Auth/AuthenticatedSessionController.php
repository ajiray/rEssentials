<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();

            $request->session()->regenerate();

            $user = Auth::user();
            
            // Check if the user is banned
            if ($user->usertype === 'banned') {
                Auth::logout(); // Log out the user if they are banned
                throw new \Exception('This email is banned. Please contact support for assistance.');
            }

            // Redirect based on user type
            if ($user->usertype == 'user') {
                return redirect()->intended(route('dashboard', [], false));
            } elseif ($user->usertype == 'admin') {
                return redirect()->intended(route('admindashboard', [], false));
            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['email' => $e->getMessage()]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
