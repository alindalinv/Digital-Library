<x-app-layout>
    @php
        $showLatestTitle = request()->has('sort') && $sort === 'latest';
        $booksPageTitle = $featured ? 'Featured Books' : ($showLatestTitle ? 'Latest Books' : 'All Books');
    @endphp

    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
            <div>
                <p class="small text-uppercase fw-semibold text-primary mb-1">Digital Library</p>
                <h2 id="books-page-title" class="h4 mb-0 fw-semibold text-dark">{{ __($booksPageTitle) }}</h2>
            </div>
            <span class="text-secondary small">{{ $books->total() }} titles to explore</span>
        </div>
    </x-slot>

    <style>
        .catalog-hero { background: linear-gradient(120deg, #eef5ff 0%, #f8fbff 55%, #fff 100%); }
        .catalog-filter { border: 1px solid #dce5f1; box-shadow: 0 0.5rem 1.5rem rgba(31, 51, 73, .06); }
        .catalog-card { transition: transform .2s ease, box-shadow .2s ease; }
        .catalog-card:hover { transform: translateY(-4px); box-shadow: 0 1rem 2rem rgba(31, 51, 73, .12) !important; }
        .catalog-cover { height: 260px; object-fit: cover; background: #edf2f7; }
        @media (max-width: 575.98px) { .catalog-cover { height: 230px; } }
    </style>

    <div class="py-5">
        <div class="container">
            <section class="catalog-hero rounded-4 p-4 p-lg-5 mb-4">
                <div class="row align-items-end g-4">
                    <div class="col-lg-6">
                        <p class="text-primary fw-semibold mb-2">Find your next read</p>
                        <h1 class="display-6 fw-bold text-dark mb-2">A better way to browse.</h1>
                        <p class="text-secondary mb-0">Search the collection, narrow by category, and sort the shelves your way.</p>
                    </div>
                    <div class="col-lg-6">
                        <form id="book-filters" method="GET" action="{{ route('books.index') }}" class="catalog-filter bg-white rounded-3 p-3">
                            <div class="row g-2">
                                <div class="col-12">
                                    <label for="book-search" class="visually-hidden">Search books</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-secondary">⌕</span>
                                        <input id="book-search" type="search" name="search" value="{{ $search }}" placeholder="Title, ISBN, or author" class="form-control border-start-0" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="book-category" class="visually-hidden">Category</label>
                                    <select id="book-category" name="category" class="form-select">
                                        <option value="">All categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="book-sort" class="visually-hidden">Sort books</label>
                                    <select id="book-sort" name="sort" class="form-select">
                                        <option value="latest" @selected($sort === 'latest')>Newest first</option>
                                        <option value="title" @selected($sort === 'title')>Title A-Z</option>
                                        <option value="oldest" @selected($sort === 'oldest')>Oldest first</option>
                                        <option value="price_asc" @selected($sort === 'price_asc')>Price: low to high</option>
                                        <option value="price_desc" @selected($sort === 'price_desc')>Price: high to low</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input id="book-featured" name="featured" value="1" type="checkbox" class="form-check-input" @checked($featured)>
                                        <label for="book-featured" class="form-check-label d-inline-flex align-items-center gap-1 text-warning-emphasis">
                                            <svg width="15" height="15" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true"><path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/></svg>
                                            Featured books only
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end gap-2">
                                    @if($search || $categoryId || $sort !== 'latest' || $featured)
                                        <a href="{{ route('books.index') }}" class="btn btn-light">Clear</a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <div id="books-summary" class="d-flex align-items-center justify-content-between mb-3">
                <h3 class="h5 fw-bold text-dark mb-0">Browse books</h3>
                <span class="small text-secondary">{{ $books->firstItem() ?? 0 }}-{{ $books->lastItem() ?? 0 }} of {{ $books->total() }}</span>
            </div>

            <div id="books-results" aria-live="polite">@include('frontend.books._results')</div>
        </div>
    </div>
@push('scripts')
    <script>
        (() => {
            const form = document.getElementById('book-filters');
            const results = document.getElementById('books-results');
            const summary = document.getElementById('books-summary');
            const pageTitle = document.getElementById('books-page-title');
            if (!form || !results) return;

            const search = form.querySelector('[name="search"]');
            const category = form.querySelector('[name="category"]');
            const sort = form.querySelector('[name="sort"]');
            const featured = form.querySelector('[name="featured"]');
            if (!search || !category || !sort || !featured) return;
            let latestView = @json($showLatestTitle);
            let timer;
            let activeRequest;

            const buildUrl = (page = 1) => {
                const url = new URL(form.action, window.location.origin);
                const params = url.searchParams;
                params.delete('page');
                if (search.value.trim()) params.set('search', search.value.trim()); else params.delete('search');
                if (category.value) params.set('category', category.value); else params.delete('category');
                if (sort.value !== 'latest' || latestView) params.set('sort', sort.value); else params.delete('sort');
                if (featured.checked) params.set('featured', '1'); else params.delete('featured');
                if (page > 1) params.set('page', page);
                return url;
            };

            const updatePageTitle = () => {
                pageTitle.textContent = featured.checked ? 'Featured Books' : (latestView ? 'Latest Books' : 'All Books');
            };

            const load = async (url, updateUrl = true) => {
                activeRequest?.abort();
                activeRequest = new AbortController();
                results.setAttribute('aria-busy', 'true');
                results.classList.add('opacity-50');
                try {
                    const response = await fetch(url, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, signal: activeRequest.signal });
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    const data = await response.json();
                    results.innerHTML = data.html;
                    summary.querySelector('span').textContent = data.total ? `${(data.current_page - 1) * data.per_page + 1}-${Math.min(data.current_page * data.per_page, data.total)} of ${data.total}` : '0-0 of 0';
                    if (updateUrl) window.history.replaceState({}, '', url);
                } catch (error) {
                    if (error.name !== 'AbortError') console.error('Book search failed.', error);
                } finally {
                    results.removeAttribute('aria-busy');
                    results.classList.remove('opacity-50');
                }
            };

            const refresh = () => load(buildUrl());
            form.addEventListener('submit', event => { event.preventDefault(); refresh(); });
            search.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(refresh, 300); });
            category.addEventListener('change', refresh);
            sort.addEventListener('change', () => { latestView = sort.value === 'latest'; updatePageTitle(); refresh(); });
            featured.addEventListener('change', () => { updatePageTitle(); refresh(); });
            results.addEventListener('click', event => {
                const link = event.target.closest('a[href*="page="]');
                if (!link) return;
                event.preventDefault();
                load(new URL(link.href, window.location.origin));
            });
        })();
    </script>
@endpush
</x-app-layout>