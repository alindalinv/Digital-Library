<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['name'] = trim("{$validated['first_name']} {$validated['last_name']}");
        $validated['status'] = true;

        $user = User::forceCreate($validated);

        // ✅ Auto-assign default role (optional)
        $user->assignRole('member');

        // ✅ Fire Registered event — this triggers the verification email
        event(new Registered($user));

        // ✅ Log the user in (so they can access verification.notice)
        Auth::login($user);

        // ✅ Redirect to "verify your email" page instead of dashboard
        return redirect()->route('verification.notice');
    }
}