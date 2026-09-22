@extends('layouts.admin.app')

@section('content')

    <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-gray-900">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">E-book Files</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload and manage files attached to books.</p>
            </div>
            @can('ebook-files.create')
                <a href="{{ route('admin.ebook-files.create') }}" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Upload file</a>
            @endcan
        </div>
        <form id="ebookFileSearchForm" class="mb-5" method="GET" action="{{ route('admin.ebook-files.index') }}">
            <input id="ebookFileSearch" name="search" value="{{ $search }}" placeholder="Search by book title or ISBN" autocomplete="off"
                class="w-full max-w-md rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm dark:border-gray-700 dark:text-white">
        </form>

        <div id="ebookFileResults">@include('admin.pages.ebook-files._results')</div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('ebookFileSearch');
            const form = document.getElementById('ebookFileSearchForm');
            const results = document.getElementById('ebookFileResults');
            let timer;
            let request;

            const search = (page = 1) => {
                request?.abort();
                request = new AbortController();
                const params = new URLSearchParams({ search: input.value, page });

                fetch(`${form.action}?${params}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    signal: request.signal,
                })
                    .then(response => response.ok ? response.json() : Promise.reject(response))
                    .then(data => { results.innerHTML = data.html; history.replaceState({}, '', `${form.action}?${params}`); })
                    .catch(error => { if (error.name !== 'AbortError') console.error('E-book search failed.', error); });
            };

            input.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(search, 300); });
            form.addEventListener('submit', event => { event.preventDefault(); search(); });
            results.addEventListener('click', event => {
                const link = event.target.closest('a[href*="page="]');
                if (!link) return;
                event.preventDefault();
                search(new URL(link.href).searchParams.get('page') || 1);
            });
        });
    </script>
@endpush
