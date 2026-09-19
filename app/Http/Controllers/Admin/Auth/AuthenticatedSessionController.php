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
        // If already authenticated, go directly to admin dashboard
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.pages.auth.signin');
    }

    /**
     * Handle admin login.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate login form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Attempt to authenticate the user
        if (
            !Auth::attempt(
                $credentials,
                $request->boolean('remember')
            )
        ) {
            return back()
                ->withErrors([
                    'email' => 'The email or password is incorrect.',
                ])
                ->onlyInput('email');
        }

        // Regenerate session after successful authentication
        $request->session()->regenerate();

        // Check admin permissions
        if (!$request->user()->hasAnyRole(['Admin', 'Super Admin'])) {

            Auth::logout();

            return back()
                ->withErrors([
                    'email' => 'You do not have permission to access the admin area.',
                ])
                ->onlyInput('email');
        }

        // Login successful
        return redirect()->intended(route('admin.dashboard'));
    }
    public function destroy(Request $request): RedirectResponse
    {
        // Logout the current user
        Auth::logout();

        // Remove the current session
        $request->session()->invalidate();

        // Generate a new CSRF token
        $request->session()->regenerateToken();

        // Go back to admin login
        return redirect()->route('admin.login');
    }
}