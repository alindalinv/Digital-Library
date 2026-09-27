<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EbookFileDownloadMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        /*
         * ---------------------------------------------------------
         * 1. Check ADMIN guard first
         * ---------------------------------------------------------
         */
        $adminUser = Auth::guard('admin')->user();

        if ($adminUser) {
            if (! $adminUser->can('ebook-files.download')) {
                abort(
                    403,
                    'You do not have permission to download ebook files.'
                );
            }

            return $next($request);
        }

        /*
         * ---------------------------------------------------------
         * 2. Check WEB guard
         * ---------------------------------------------------------
         */
        $webUser = Auth::guard('web')->user();

        if ($webUser) {
            if (! $webUser->can('ebook-files.download')) {
                abort(
                    403,
                    'You do not have permission to download ebook files.'
                );
            }

            return $next($request);
        }

        /*
         * ---------------------------------------------------------
         * 3. Not authenticated
         * ---------------------------------------------------------
         */
        return redirect()->route('login');
    }
}