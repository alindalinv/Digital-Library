<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the admin login page.
     */
    public function create(): View|RedirectResponse
    {
        // Only redirect if already authenticated on the ADMIN guard.
        // A logged-in frontend user will still see the admin login form.
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.pages.auth.signin');
    }

    /**
     * Handle admin login.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Authenticate against the admin guard only
        if (! Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'The email or password is incorrect.'])
                ->onlyInput('email');
        }

        // Check role on the admin-guard user
        $user = Auth::guard('admin')->user();

        if (! $user->hasAnyRole(['Admin', 'Super Admin'])) {
            Auth::guard('admin')->logout();
            $request->session()->regenerate(); // keep frontend session intact

            return back()
                ->withErrors([
                    'email' => 'You do not have permission to access the admin area.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Log out of the admin guard only.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        // Do NOT invalidate the whole session — that would log the
        // frontend user out too. Just rotate the CSRF token.
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}