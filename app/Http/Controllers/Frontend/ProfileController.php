<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated web user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = Auth::guard('web')->user();

        abort_unless($user, 403);

        return view('frontend.profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update personal information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        abort_unless($user, 403);

        $validated = $request->validated();

        /*
         * Handle profile photo upload.
         */
        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists.
            if (
                $user->photo &&
                Storage::disk('public')->exists($user->photo)
            ) {
                Storage::disk('public')->delete($user->photo);
            }

            $validated['photo'] = $request
                ->file('photo')
                ->store('avatars', 'public');
        }

        /*
         * Update user information.
         */
        $user->fill($validated);

        /*
         * Reset email verification when email changes.
         */
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update social links.
     */
    public function updateSocial(Request $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        abort_unless($user, 403);

        $validated = $request->validate([
            'facebook' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
        ]);

        /*
         * Convert empty strings to null.
         */
        $validated = array_map(
            fn ($value) => $value ?: null,
            $validated
        );

        $user->update($validated);

        return Redirect::route('profile.edit')
            ->with('status', 'social-updated')
            ->with('success', 'Social links updated successfully.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        abort_unless($user, 403);

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => [
                'required',
                'current_password:web',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        /*
         * Keep the current web session alive.
         */
        Auth::guard('web')->login($user);

        return Redirect::route('profile.edit')
            ->with('status', 'password-updated')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Delete the authenticated web user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::guard('web')->user();

        abort_unless($user, 403);

        $request->validateWithBag('userDeletion', [
            'password' => [
                'required',
                'current_password:web',
            ],
        ]);

        /*
         * Delete profile photo.
         */
        if (
            $user->photo &&
            Storage::disk('public')->exists($user->photo)
        ) {
            Storage::disk('public')->delete($user->photo);
        }

        /*
         * Logout ONLY the web guard.
         *
         * Do not use Auth::logout() because your application
         * also has a separate admin guard.
         */
        Auth::guard('web')->logout();

        /*
         * Delete the user account.
         */
        $user->delete();

        /*
         * Destroy the current session.
         */
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')
            ->with('status', 'account-deleted')
            ->with(
                'success',
                'Your account has been deleted. We\'re sorry to see you go!'
            );
    }
}