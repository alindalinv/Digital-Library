<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $auth = Auth::guard('admin');

        // 1. Not logged in on the admin guard → admin login
        if (! $auth->check()) {
            return redirect()->route('admin.login');
        }

        $user = $auth->user();

        // 2. Account disabled → logout admin guard only
        if ((int) $user->status !== 1) {
            $auth->logout();
            $request->session()->regenerate();   // keep frontend session

            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Your account has been disabled.']);
        }

        // 3. Not an admin → 403
        if (! $user->can('admin.access')) {
            abort(403, 'You do not have admin access.');
        }

        return $next($request);
    }
}