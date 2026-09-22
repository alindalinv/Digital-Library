{{-- =========================================================
     Flash Messages
========================================================== --}}
@if (session('success'))
        <div x-data="{ open: true }" x-show="open" x-transition.opacity.duration.200ms role="alert" aria-live="polite"
            class="mb-4 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 shadow-sm dark:border-green-900/50 dark:bg-green-900/10">

        <svg class="mt-0.5 h-5 w-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>

        <div class="flex-1">
            <p class="text-sm font-medium text-green-800 dark:text-green-400">
                {{ session('success') }}
            </p>
        </div>

        <button type="button"
                x-on:click="open = false"
                class="rounded-lg p-1.5 text-green-500 transition hover:bg-green-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500/40 dark:hover:bg-green-900/30"
                aria-label="Dismiss success message">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
@endif

@if (session('error'))
        <div x-data="{ open: true }" x-show="open" x-transition.opacity.duration.200ms role="alert" aria-live="polite"
            class="mb-4 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm dark:border-red-900/50 dark:bg-red-900/10">

        <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4m0 4h.01M10.29 3.86l-7.82 13.5A2 2 0 004.2 20.36h15.6a2 2 0 001.73-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>

        <div class="flex-1">
            <p class="text-sm font-medium text-red-800 dark:text-red-400">
                {{ session('error') }}
            </p>
        </div>

        <button type="button"
                x-on:click="open = false"
                class="rounded-lg p-1.5 text-red-500 transition hover:bg-red-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500/40 dark:hover:bg-red-900/30"
                aria-label="Dismiss error message">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
@endif

@if (session('warning'))
        <div x-data="{ open: true }" x-show="open" x-transition.opacity.duration.200ms role="alert" aria-live="polite"
            class="mb-4 flex items-start gap-3 rounded-xl border border-yellow-200 bg-yellow-50 p-4 shadow-sm dark:border-yellow-900/50 dark:bg-yellow-900/10">
        <svg class="mt-0.5 h-5 w-5 shrink-0 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div class="flex-1">
            <p class="text-sm font-medium text-yellow-800 dark:text-yellow-400">
                {{ session('warning') }}
            </p>
        </div>
        <button type="button" x-on:click="open = false"
                class="rounded-lg p-1.5 text-yellow-500 transition hover:bg-yellow-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-yellow-500/40 dark:hover:bg-yellow-900/30"
                aria-label="Dismiss warning message">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
@endif

@if (session('info'))
        <div x-data="{ open: true }" x-show="open" x-transition.opacity.duration.200ms role="alert" aria-live="polite"
            class="mb-4 flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4 shadow-sm dark:border-blue-900/50 dark:bg-blue-900/10">
        <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="flex-1">
            <p class="text-sm font-medium text-blue-800 dark:text-blue-400">
                {{ session('info') }}
            </p>
        </div>
        <button type="button" x-on:click="open = false"
                class="rounded-lg p-1.5 text-blue-500 transition hover:bg-blue-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500/40 dark:hover:bg-blue-900/30"
                aria-label="Dismiss information message">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
@endif