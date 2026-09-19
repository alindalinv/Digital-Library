<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        // Not logged in
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        // Account disabled
        if (! $user->status) {
            auth()->logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Your account has been disabled.',
                ]);
        }

        // Not an admin
        if (! $user->can('admin.access')) {
            abort(403);
        }

        return $next($request);
    }
}