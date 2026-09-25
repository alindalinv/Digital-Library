@extends('layouts.admin.app')

@section('content')

        {{-- =========================================================
        Header
        ========================================================== --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Trashed Books
                </h3>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    Books you've deleted. Restore them or permanently remove them.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.books.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>

                    Back to Books
                </a>
            </div>
        </div>


        {{-- =========================================================
        Search Bar
        ========================================================== --}}
        <div
            class="mb-5 flex flex-col gap-4 border-b border-gray-200 pb-5 lg:flex-row lg:items-center lg:justify-between dark:border-gray-800">

            <div class="flex items-center gap-2">
                <span
                    class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400">
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Trash
                </span>
            </div>

            <div class="relative w-full lg:w-72">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>

                <input type="text" id="trashedSearch" name="search" value="{{ request('search') }}"
                    placeholder="Search trashed books..." autocomplete="off"
                    class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-9 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">

                <span id="searchSpinner"
                    class="pointer-events-none absolute right-3 top-1/2 hidden -translate-y-1/2 text-gray-400">
                    <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </span>

                <button type="button" id="searchClear"
                    class="absolute right-3 top-1/2 hidden -translate-y-1/2 rounded text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                    aria-label="Clear search">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>


        {{-- =========================================================
        Results
        ========================================================== --}}
        <div id="trashedResults">
            @include('admin.books._trashed_results', ['books' => $books, 'search' => $search ?? ''])
        </div>


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const searchInput = document.getElementById('trashedSearch');
                const searchClear = document.getElementById('searchClear');
                const searchSpinner = document.getElementById('searchSpinner');
                const resultsBox = document.getElementById('trashedResults');

                let state = {
                    search: @json($search ?? ''),
                    page:   {{ $books->currentPage() }},
                };

                let debounceTimer = null;
                let activeRequest = null;

                /* -----------------------------------------------------
                 * 1. Sync clear button visibility
                 * --------------------------------------------------- */
                const syncUI = () => {
                    if (searchInput.value.length > 0) {
                        searchClear.classList.remove('hidden');
                    } else {
                        searchClear.classList.add('hidden');
                    }
                };

                /* -----------------------------------------------------
                 * 2. Build URL
                 * --------------------------------------------------- */
                const buildUrl = () => {
                    const params = new URLSearchParams();
                    if (state.search) params.set('search', state.search);
                    if (state.page > 1) params.set('page', state.page);

                    const qs = params.toString();
                    return `${window.location.pathname}${qs ? '?' + qs : ''}`;
                };

                /* -----------------------------------------------------
                 * 3. Fetch results
                 * --------------------------------------------------- */
                const fetchResults = async () => {
                    if (activeRequest) activeRequest.abort();
                    activeRequest = new AbortController();
                    searchSpinner.classList.remove('hidden');

                    try {
                        const response = await fetch(buildUrl(), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            signal: activeRequest.signal,
                        });

                        if (!response.ok) throw new Error(`HTTP ${response.status}`);

                        const data = await response.json();
                        resultsBox.innerHTML = data.html;

                        history.replaceState(null, '', buildUrl());
                        bindActions();
                    } catch (err) {
                        if (err.name === 'AbortError') return;
                        console.error('Search failed:', err);
                        resultsBox.innerHTML = `
                        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-900/10 dark:text-red-400">
                            Failed to load results. Please try again.
                        </div>`;
                    } finally {
                        searchSpinner.classList.add('hidden');
                    }
                };

                /* -----------------------------------------------------
                 * 4. Debounced search
                 * --------------------------------------------------- */
                searchInput?.addEventListener('input', (e) => {
                    state.search = e.target.value.trim();
                    state.page = 1;
                    syncUI();

                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(fetchResults, 350);
                });

                searchClear?.addEventListener('click', () => {
                    searchInput.value = '';
                    state.search = '';
                    state.page = 1;
                    syncUI();
                    fetchResults();
                    searchInput.focus();
                });

                /* -----------------------------------------------------
                 * 5. Pagination (delegated)
                 * --------------------------------------------------- */
                resultsBox?.addEventListener('click', (e) => {
                    const link = e.target.closest('a[href]');
                    if (!link) return;

                    const url = new URL(link.href, window.location.origin);
                    if (url.origin !== window.location.origin) return;
                    if (!url.searchParams.has('page')) return;

                    e.preventDefault();
                    state.page = parseInt(url.searchParams.get('page'), 10) || 1;
                    fetchResults();

                    resultsBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });

                /* -----------------------------------------------------
                 * 6. Restore & Force Delete actions
                 * --------------------------------------------------- */
                const bindActions = () => {
                    // Restore
                    document.querySelectorAll('[data-restore-book]').forEach((btn) => {
                        btn.addEventListener('click', async () => {
                            const { bookId, bookTitle } = btn.dataset;

                            if (!confirm(`Restore "${bookTitle}"?`)) return;

                            btn.disabled = true;
                            try {
                                const response = await fetch(`/admin/books/${bookId}/restore`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json',
                                    },
                                });

                                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                                fetchResults();
                            } catch (err) {
                                console.error('Restore failed:', err);
                                alert('Failed to restore the book. Please try again.');
                                btn.disabled = false;
                            }
                        });
                    });

                    // Force Delete
                    document.querySelectorAll('[data-force-delete-book]').forEach((btn) => {
                        btn.addEventListener('click', async () => {
                            const { bookId, bookTitle } = btn.dataset;

                            if (!confirm(`Permanently delete "${bookTitle}"? This cannot be undone.`)) return;

                            btn.disabled = true;
                            try {
                                const response = await fetch(`/admin/books/${bookId}/force-delete`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json',
                                    },
                                });

                                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                                fetchResults();
                            } catch (err) {
                                console.error('Force delete failed:', err);
                                alert('Failed to delete the book permanently. Please try again.');
                                btn.disabled = false;
                            }
                        });
                    });
                };

                /* -----------------------------------------------------
                 * 7. Init
                 * --------------------------------------------------- */
                syncUI();
                bindActions();
            });
        </script>
    @endpush
@endsection