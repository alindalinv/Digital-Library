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

            /*
            |--------------------------------------------------------------------------
            | Public / Frontend Routes
            |--------------------------------------------------------------------------
            */

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->group(base_path('routes/frontend.php'));


            /*
            |--------------------------------------------------------------------------
            | Authentication Routes
            |--------------------------------------------------------------------------
            */

            Route::middleware('web')
                ->group(base_path('routes/auth.php'));


            /*
            |--------------------------------------------------------------------------
            | Admin Routes
            |--------------------------------------------------------------------------
            */

            // Authentication is assigned inside routes/admin.php so the
            // login routes remain reachable by guests.
            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));
        },
    )

    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | Middleware Aliases
        |--------------------------------------------------------------------------
        */

        $middleware->alias([

            // Custom Admin middleware
            'admin' => \App\Http\Middleware\AdminMiddleware::class,

            // Spatie Permission
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,

            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,

            'role_or_permission' =>
                \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        // `guest:admin` uses this destination when an admin is already
        // authenticated. Keep frontend guests on the frontend dashboard.
        $middleware->redirectUsersTo(
            fn (\Illuminate\Http\Request $request) => $request->is('admin/*')
                ? route('admin.dashboard')
                : route('dashboard')
        );


        /*
        |--------------------------------------------------------------------------
        | Proxy / HTTPS
        |--------------------------------------------------------------------------
        */

        $middleware->trustProxies(at: '*');
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();
