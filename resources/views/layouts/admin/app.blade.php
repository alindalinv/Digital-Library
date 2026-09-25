<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    class="h-full bg-gray-50 dark:bg-gray-900">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} | {{ config('app.name') }}</title>
    <!-- Theme Store -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        html {
            overflow-x: hidden;
        }

        body {
            min-height: 100%;
            overflow-x: hidden;
        }
    </style>
    {{-- Alpine Stores --}}
    <script>
        document.addEventListener('alpine:init', () => {

            /*
            * ==========================================================
            * Theme Store
            * ==========================================================
            */
            Alpine.store('theme', {

                theme: 'light',
                resolvedTheme: 'light',

                init() {
                    const savedTheme = localStorage.getItem('theme');

                    this.theme = savedTheme === 'dark'
                        ? 'dark'
                        : 'light';

                    this.updateTheme();
                },

                set(value) {
                    this.theme = value === 'dark'
                        ? 'dark'
                        : 'light';

                    localStorage.setItem('theme', this.theme);

                    this.updateTheme();

                    window.dispatchEvent(
                        new CustomEvent('theme-changed', {
                            detail: this.theme
                        })
                    );
                },

                toggle() {
                    this.set(
                        this.resolvedTheme === 'dark'
                            ? 'light'
                            : 'dark'
                    );
                },

                updateTheme() {
                    const html = document.documentElement;
                    const isDark = this.theme === 'dark';

                    html.classList.toggle('dark', isDark);

                    this.resolvedTheme = isDark
                        ? 'dark'
                        : 'light';

                    html.setAttribute(
                        'data-color-scheme',
                        this.resolvedTheme
                    );

                    html.dataset.theme = this.resolvedTheme;
                    html.style.colorScheme = this.resolvedTheme;

                    if (document.body) {
                        document.body.dataset.theme = this.resolvedTheme;
                        document.body.style.colorScheme =
                            this.resolvedTheme;
                    }
                }
            });


            /*
            * ==========================================================
            * Sidebar Store
            * ==========================================================
            */
            Alpine.store('sidebar', {

                breakpoint: 1280,

                isExpanded: false,
                isMobileOpen: false,
                isHovered: false,

                init() {
                    this.syncWithViewport();

                    window.addEventListener(
                        'resize',
                        this.handleResize.bind(this),
                        { passive: true }
                    );
                },

                isDesktop() {
                    return window.innerWidth >= this.breakpoint;
                },

                syncWithViewport() {

                    if (this.isDesktop()) {

                        const savedState =
                            localStorage.getItem(
                                'sidebarExpanded'
                            );

                        this.isExpanded =
                            savedState === null
                                ? true
                                : savedState === 'true';

                        this.isMobileOpen = false;

                    } else {

                        this.isExpanded = false;
                        this.isMobileOpen = false;
                        this.isHovered = false;
                    }
                },

                handleResize() {
                    this.syncWithViewport();
                },

                toggleExpanded() {

                    if (!this.isDesktop()) {
                        return;
                    }

                    this.isExpanded = !this.isExpanded;
                    this.isHovered = false;

                    localStorage.setItem(
                        'sidebarExpanded',
                        this.isExpanded
                    );
                },

                toggleMobileOpen() {

                    if (this.isDesktop()) {
                        return;
                    }

                    this.isMobileOpen =
                        !this.isMobileOpen;
                },

                setMobileOpen(value) {
                    this.isMobileOpen =
                        Boolean(value);
                },

                setHovered(value) {

                    if (
                        this.isDesktop() &&
                        !this.isExpanded
                    ) {
                        this.isHovered =
                            Boolean(value);
                    }
                }
            });
        });
    </script>
    <!-- Scripts -->
    @vite([
        'resources/assets/admin/css/app.css',
        'resources/assets/admin/js/app.js'
    ])

    <!-- TinyMCE -->
    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
</head>

<body x-data="{ loaded: true }"
    class="min-h-full bg-gray-50 text-gray-800 antialiased dark:bg-gray-900 dark:text-gray-200">

    {{-- preloader --}}
    <x-admin.common.preloader />
    {{-- preloader end --}}

    <div class="min-h-screen">
        @include('layouts.admin.backdrop')
        @include('layouts.admin.sidebar')

        <div class="min-h-screen transition-[margin] duration-300 ease-in-out" 
            :class="{
                'xl:ml-[290px]':
                    $store.sidebar.isExpanded ||
                    $store.sidebar.isHovered,
                'xl:ml-[90px]':
                    !$store.sidebar.isExpanded &&
                    !$store.sidebar.isHovered,
                'ml-0':
                    !$store.sidebar.isMobileOpen
            }">
            <!-- app header start -->
            @include('layouts.admin.app-header')
            <!-- app header end -->
            <main class="min-h-[calc(100vh-64px)] bg-gray-50 text-gray-800 transition-colors duration-200 dark:bg-gray-900 dark:text-gray-200">
                <div class="mx-auto w-full max-w-(--breakpoint-2xl) px-4 py-4 sm:px-5 sm:py-5 lg:px-6 lg:py-6">
                    <x-admin.common.page-breadcrumb :pageTitle="$title ?? 'Page'" />
                    {{-- Flash messages (visible on every page) --}}
                    @include('admin.partials.flash')
                    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <div class="border-b border-gray-200 px-5 py-5 lg:px-6 dark:border-gray-800">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </main>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    @stack('scripts')
</body>

</html>