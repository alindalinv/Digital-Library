<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        // Always use the dedicated admin guard.
        $guard = Auth::guard('admin');

        // Authentication safety check.
        if (! $guard->check()) {
            return redirect()->route('admin.login');
        }

        /** @var \App\Models\User $user */
        $user = $guard->user();

        // Account status check.
        if (! $user->status) {
            $guard->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->withErrors([
                    'email' => 'Your account has been disabled.',
                ]);
        }

        // Admin authorization.
        // admin.access must exist under the "admin" guard.
        if (! $user->hasPermissionTo('admin.access', 'admin')) {
            abort(403, 'You do not have admin access.');
        }

        return $next($request);
    }
}