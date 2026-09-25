@extends('layouts.admin.app')

@section('content')

    @php
        $statusTabs = [
            'all' => 'All',
            'published' => 'Published',
            'draft' => 'Draft',
            'archived' => 'Archived',
            'featured' => $featured,
        ];

        $activeStatus = request('status', 'all');

        $statusStyles = [
            'draft' => [
                'label' => 'Draft',
                'class' => 'inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                'dot' => 'bg-gray-400',
            ],
            'published' => [
                'label' => 'Published',
                'class' => 'inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400',
                'dot' => 'bg-green-500',
            ],
            'archived' => [
                'label' => 'Archived',
                'class' => 'inline-flex items-center gap-1.5 rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                'dot' => 'bg-yellow-500',
            ],
        ];
    @endphp

    <div
        class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">

        {{-- =========================================================
        Header
        ========================================================== --}}
        <div
            class="flex flex-col gap-4 border-b border-gray-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between lg:px-6 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                            d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        Books
                    </h3>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                        Manage your library catalog.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 sm:w-auto sm:flex-row">
                <a href="{{ route('admin.books.trashed') }}"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg sm:w-auto border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:border-gray-400 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400/30 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>

                    Trash
                </a>

                <a href="{{ route('admin.books.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-blue-500/30">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>

                    Add Book
                </a>
            </div>
        </div>


        {{-- =========================================================
        Filters Bar: Status tabs + Featured toggle + Search
        ========================================================== --}}
        <div
            class="mx-5 my-5 flex flex-col gap-4 rounded-xl border border-gray-200 bg-gray-50/70 p-3.5 lg:flex-row lg:items-center lg:justify-between dark:border-gray-800 dark:bg-gray-800/30">

            <div class="flex flex-wrap items-center gap-3">

                {{-- Status Tabs --}}
                <div class="flex flex-wrap items-center gap-1 rounded-lg bg-white p-1 shadow-sm ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-700"
                    role="tablist" aria-label="Filter books by status">

                    @foreach ($statusTabs as $value => $label)
                                <button type="button" role="tab" data-status="{{ $value }}"
                                    aria-selected="{{ $activeStatus === $value ? 'true' : 'false' }}" class="status-tab rounded-md px-3 py-1.5 text-sm font-medium transition
                                                                                                            {{ $activeStatus === $value
                        ? 'bg-blue-50 text-blue-700 shadow-sm dark:bg-blue-500/10 dark:text-blue-400'
                        : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
                                    {{ $label }}
                                </button>
                    @endforeach
                </div>


                {{-- Featured Toggle --}}
                <button type="button" id="featuredFilter" data-featured="{{ $featured ? 'true' : 'false' }}"
                    aria-pressed="{{ $featured ? 'true' : 'false' }}"
                    class="featured-toggle inline-flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-sm font-medium transition
                                           {{ $featured
        ? 'border-amber-300 bg-amber-50 text-amber-700 dark:border-amber-700 dark:bg-amber-900/30 dark:text-amber-400'
        : 'border-gray-300 bg-white text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">

                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L1.078 10.1c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>

                    Featured only
                </button>

            </div>


            {{-- Search --}}
            <div class="relative w-full lg:w-80">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>

                <input type="text" id="bookSearch" name="search" value="{{ request('search') }}"
                    placeholder="Search by title or ISBN..." autocomplete="off"
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
        Results container (AJAX replaces inner content)
        ========================================================== --}}
        <div id="booksResults" class="px-5 pb-5 lg:px-6 lg:pb-6">
            @include('admin.books._results', ['books' => $books, 'search' => $search, 'status' => $status])
        </div>

    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const searchInput = document.getElementById('bookSearch');
                const searchClear = document.getElementById('searchClear');
                const searchSpinner = document.getElementById('searchSpinner');
                const resultsBox = document.getElementById('booksResults');
                const statusTabs = document.querySelectorAll('.status-tab');
                const featuredFilter = document.getElementById('featuredFilter');

                const state = {
                    status: @json($activeStatus),
                    search: @json($search),
                    featured: @json($featured ? true : false),  // 👈 new
                    page:     {{ $books->currentPage() }},
                };

                let debounceTimer = null;
                let activeRequest = null;

                /* -----------------------------------------------------
                 * 1. Sync UI (status tabs + featured toggle + clear btn)
                 * --------------------------------------------------- */
                const syncUI = () => {
                    // Clear button
                    if (searchInput.value.length > 0) {
                        searchClear.classList.remove('hidden');
                    } else {
                        searchClear.classList.add('hidden');
                    }

                    // Status tabs
                    statusTabs.forEach((tab) => {
                        const isActive = tab.dataset.status === state.status;
                        tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                        tab.classList.toggle('bg-white', isActive);
                        tab.classList.toggle('shadow-sm', isActive);
                        tab.classList.toggle('text-gray-800', isActive);
                        tab.classList.toggle('dark:bg-gray-900', isActive);
                        tab.classList.toggle('dark:text-white/90', isActive);
                        tab.classList.toggle('text-gray-500', !isActive);
                        tab.classList.toggle('dark:text-gray-400', !isActive);
                    });

                    // Featured toggle
                    if (featuredFilter) {
                        featuredFilter.dataset.featured = state.featured ? 'true' : 'false';
                        featuredFilter.setAttribute('aria-pressed', state.featured ? 'true' : 'false');

                        // Active styles
                        featuredFilter.classList.toggle('border-purple-300', state.featured);
                        featuredFilter.classList.toggle('bg-purple-50', state.featured);
                        featuredFilter.classList.toggle('text-purple-700', state.featured);
                        featuredFilter.classList.toggle('dark:border-purple-700', state.featured);
                        featuredFilter.classList.toggle('dark:bg-purple-900/30', state.featured);
                        featuredFilter.classList.toggle('dark:text-purple-400', state.featured);

                        // Inactive styles
                        featuredFilter.classList.toggle('border-gray-300', !state.featured);
                        featuredFilter.classList.toggle('bg-white', !state.featured);
                        featuredFilter.classList.toggle('text-gray-600', !state.featured);
                        featuredFilter.classList.toggle('dark:border-gray-700', !state.featured);
                        featuredFilter.classList.toggle('dark:bg-gray-800', !state.featured);
                        featuredFilter.classList.toggle('dark:text-gray-300', !state.featured);
                    }
                };

                /* -----------------------------------------------------
                 * 2. Build URL
                 * --------------------------------------------------- */
                const buildUrl = () => {
                    const params = new URLSearchParams();

                    if (state.search) params.set('search', state.search);
                    if (state.status && state.status !== 'all') params.set('status', state.status);
                    if (state.featured) params.set('featured', '1'); // 👈 new
                    if (state.page > 1) params.set('page', state.page);

                    const qs = params.toString();
                    return `${window.location.pathname}${qs ? '?' + qs : ''}`;
                };

                /* -----------------------------------------------------
                 * 3. Fetch results (unchanged)
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
                        bindDeleteButtons();
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
                 * 4. Search input
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
                 * 5. Status tabs
                 * --------------------------------------------------- */
                statusTabs.forEach((tab) => {
                    tab.addEventListener('click', () => {
                        state.status = tab.dataset.status;
                        state.page = 1;
                        syncUI();
                        fetchResults();
                    });
                });

                /* -----------------------------------------------------
                 * 6. Featured toggle  👈 new
                 * --------------------------------------------------- */
                featuredFilter?.addEventListener('click', () => {
                    state.featured = !state.featured;
                    state.page = 1;
                    syncUI();
                    fetchResults();
                });

                /* -----------------------------------------------------
                 * 7. Pagination (delegated, unchanged)
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
                 * 8. Delete buttons (unchanged)
                 * --------------------------------------------------- */
                const bindDeleteButtons = () => {
                    document.querySelectorAll('[data-delete-book]').forEach((btn) => {
                        btn.addEventListener('click', async () => {
                            const { bookId, bookTitle, redirectUrl } = btn.dataset;

                            if (!bookId) {
                                console.error('Book ID is missing.');
                                return;
                            }

                            if (!redirectUrl) {
                                console.error('Trash redirect URL is missing.');
                                alert('Trash page URL is missing.');
                                return;
                            }

                            if (!confirm(`Move "${bookTitle}" to trash?`)) {
                                return;
                            }

                            btn.disabled = true;

                            try {
                                const csrfToken = document.querySelector(
                                    'meta[name="csrf-token"]'
                                )?.content;

                                if (!csrfToken) {
                                    throw new Error('CSRF token not found.');
                                }

                                const response = await fetch(
                                    `/admin/books/${encodeURIComponent(bookId)}`,
                                    {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken,
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json',
                                        },
                                    }
                                );

                                const data = await response.json();

                                if (!response.ok || !data.success) {
                                    throw new Error(
                                        data.message || `HTTP ${response.status}`
                                    );
                                }

                                // Delete successful → go directly to Trash
                                window.location.assign(redirectUrl);

                            } catch (err) {
                                console.error('Delete failed:', err);

                                alert(
                                    err.message ||
                                    'Failed to move the book to trash. Please try again.'
                                );

                                btn.disabled = false;
                            }
                        });
                    });
                };

                /* -----------------------------------------------------
                 * 9. Init
                 * --------------------------------------------------- */
                syncUI();
                bindDeleteButtons();
            });
        </script>
    @endpush
@endsection