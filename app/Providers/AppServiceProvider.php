<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Compute once per request
            if (View::shared('title')) {
                return;
            }

            $appName = config('app.name');
            $separator = ' — ';

            // Route info
            $routeName = request()->route()?->getName() ?? '';
            $routePrefix = '';

            if ($routeName) {
                // Everything before the last segment: "books.show" → "books"
                $prefix = Str::beforeLast($routeName, '.');

                // Only use it if there's an actual prefix (avoids empty "home" case)
                if ($prefix && $prefix !== $routeName) {
                    $routePrefix = Str::headline($prefix);   // "books" → "Books"
                }
            }

            // Title passed from controller
            $rawTitle = $view->getData()['title'] ?? null;

            // Fallback: derive from route name if no title passed
            if (empty($rawTitle)) {
                $rawTitle = $routeName
                    ? Str::of($routeName)->afterLast('.')->headline()->toString()
                    : $appName;
            }

            // Build the final title
            $parts = array_filter([$routePrefix, $rawTitle]);
            $title = implode(' · ', $parts);

            // Append app name if not already present
            if (!Str::contains($title, $appName)) {
                $title .= $separator . $appName;
            }

            View::share('title', $title);
        });
    }
}
