@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$title}}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite([
        'resources/assets/frontend/css/app.css',
        'resources/assets/frontend/js/app.js',
    ])
</head>

<body>
    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-4 bg-light">
        <div class="mb-4">
            <a href="/">
                <x-frontend.application-logo class="img-fluid" style="width: 80px; height: 80px;" />
            </a>
        </div>

        <div class="w-100 px-4">
            <div class="card shadow-sm border-0 mx-auto" style="max-width: 28rem;">
                <div class="card-body p-4">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>