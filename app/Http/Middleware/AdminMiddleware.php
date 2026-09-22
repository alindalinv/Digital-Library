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

        $auth = Auth::guard('admin');


        /*
        |--------------------------------------------------------------------------
        | 1. Admin authentication
        |--------------------------------------------------------------------------
        */

        if (! $auth->check()) {
            return redirect()->route('admin.login');
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Get admin user
        |--------------------------------------------------------------------------
        */

        $user = $auth->user();

        if (! $user) {
            $auth->logout();

            return redirect()->route('admin.login');
        }


        /*
        |--------------------------------------------------------------------------
        | 3. Check account status
        |--------------------------------------------------------------------------
        */

        if ((int) $user->status !== 1) {

            $auth->logout();

            return redirect()
                ->route('admin.login')
                ->withErrors([
                    'email' => 'Your account has been disabled.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Check admin permission
        |--------------------------------------------------------------------------
        */

        if (! $user->can('admin.access')) {
            abort(403, 'You do not have admin access.');
        }


        /*
        |--------------------------------------------------------------------------
        | 5. Continue
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}