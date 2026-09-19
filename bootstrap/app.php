<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',

        using: function () {

            // Public routes
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Frontend/member routes
            Route::middleware('web')
                ->group(base_path('routes/frontend.php'));

            // Admin routes
            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));

            // Authentication routes
            Route::middleware('web')
                ->group(base_path('routes/auth.php'));
        },
    )

    ->withMiddleware(function (Middleware $middleware): void {

        // Register middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();