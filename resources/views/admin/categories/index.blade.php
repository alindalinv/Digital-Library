@extends('layouts.admin.app')

@section('content')
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div><h3 class="text-lg font-semibold text-gray-800 dark:text-white">Categories</h3><p class="mt-1 text-sm text-gray-500">Organize books into clear groups.</p></div>
            @can('categories.create')<a href="{{ route('admin.categories.create') }}" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Add category</a>@endcan
        </div>

        <form id="category-filters" method="GET" action="{{ route('admin.categories.index') }}" class="mb-5 flex flex-col gap-3 sm:flex-row"><input name="search" value="{{ $search }}" placeholder="Search categories" class="w-full max-w-md rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm dark:border-gray-700 dark:text-white"><select name="status" class="rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm dark:border-gray-700 dark:text-white"><option value="all" @selected($status === 'all')>All statuses</option><option value="active" @selected($status === 'active')>Active</option><option value="inactive" @selected($status === 'inactive')>Inactive</option></select></form>
        <div id="category-results">@include('admin.categories._results', ['categories' => $categories])</div>
@endsection

@push('scripts')
    <script>
        (() => {
            const form = document.getElementById('category-filters');
            const results = document.getElementById('category-results');
            const endpoint = new URL(form.action, window.location.origin);
            let timer;

            const fetchResults = (url) => {
                fetch(url, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                })
                    .then(response => response.json())
                    .then(data => { results.innerHTML = data.html; window.history.replaceState({}, '', url); });
            };

            const search = () => {
                const params = new URLSearchParams(new FormData(form));
                if (!params.get('search')?.trim()) {
                    params.delete('search');
                }
                if (params.get('status') === 'all') {
                    params.delete('status');
                }
                params.delete('page');
                endpoint.search = params.toString();
                fetchResults(endpoint.toString());
            };

            form.addEventListener('submit', event => { event.preventDefault(); search(); });
            form.search.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(search, 300); });
            form.status.addEventListener('change', search);
            results.addEventListener('click', event => {
                const link = event.target.closest('a[href*="page="]');
                if (!link) return;
                event.preventDefault();
                fetchResults(link.href);
            });
        })();
    </script>
@endpush
