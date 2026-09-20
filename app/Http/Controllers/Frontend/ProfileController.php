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
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('frontend.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /* -----------------------------------------------------------------
     |  Update: Personal Information (name, email, phone, bio, etc.)
     | ----------------------------------------------------------------- */

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $validated['photo'] = $request->file('photo')
                ->store('avatars', 'public');
        }

        $user->fill($validated);

        // Reset email verification if email changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated')
            ->with('success', 'Profile updated successfully.');
    }

    /* -----------------------------------------------------------------
     |  Update: Social Links
     | ----------------------------------------------------------------- */

    public function updateSocial(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'facebook' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
        ]);

        // Convert empty strings to null
        $validated = array_map(
            fn($value) => $value ?: null,
            $validated
        );

        $request->user()->update($validated);

        return Redirect::route('profile.edit')
            ->with('status', 'social-updated')
            ->with('success', 'Social links updated successfully.');
    }

    /* -----------------------------------------------------------------
     |  Update: Password
     | ----------------------------------------------------------------- */

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return Redirect::route('profile.edit')
            ->with('status', 'password-updated')
            ->with('success', 'Password updated successfully.');
    }

    /* -----------------------------------------------------------------
     |  Delete: Account
     | ----------------------------------------------------------------- */

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete profile photo
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')
            ->with('status', 'account-deleted')
            ->with('success', 'Your account has been deleted. We\'re sorry to see you go!');
    }
}