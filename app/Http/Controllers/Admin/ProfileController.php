<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminProfileUpdateRequest;
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
     * Show the admin profile page.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile.index', [
            'title' => 'Edit Profile',
            'user'  => $request->user('admin'),
        ]);
    }

    /**
     * Update the admin's profile information.
     *
     * Handles both modals:
     *   - Profile header (photo, name, job, org, social links)
     *   - Personal info (email, phone, gender, DOB, bio, address)
     */
    public function update(AdminProfileUpdateRequest $request): RedirectResponse
    {
        return $this->saveProfile($request);
    }

    /**
     * Update the profile header: photo, name, work details, and social links.
     */
    public function updateHeader(AdminProfileUpdateRequest $request): RedirectResponse
    {
        return $this->saveProfile($request);
    }

    private function saveProfile(AdminProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user('admin');
        $validated = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $validated['photo'] = $request->file('photo')
                ->store('avatars', 'public');
        }

        // Normalize empty social links to null
        foreach (['facebook', 'twitter', 'linkedin', 'instagram'] as $field) {
            if (array_key_exists($field, $validated) && empty($validated[$field])) {
                $validated[$field] = null;
            }
        }

        $user->fill($validated);

        // Reset email verification if email changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('admin.profile.edit')
            ->with('status', 'profile-updated')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the admin's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password:admin'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user('admin')->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return Redirect::route('admin.profile.edit')
            ->with('status', 'password-updated')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Delete the admin's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password:admin'],
        ]);

        $user = $request->user('admin');

        // Delete profile photo
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        Auth::guard('admin')->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')
            ->with('status', 'account-deleted')
            ->with('success', 'Your account has been deleted.');
    }
}
