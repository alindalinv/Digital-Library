@extends('layouts.admin.app')

@section('content')
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="border-b border-gray-200 px-5 py-5 dark:border-gray-800 lg:px-6">
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Audit Logs</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Review administrative activity and record changes.</p>
        </div>
        <form id="audit-filters" action="{{ route('admin.audit-logs.index') }}" class="flex flex-col gap-3 border-b border-gray-200 bg-gray-50/70 p-4 sm:flex-row sm:flex-wrap dark:border-gray-800 dark:bg-gray-800/30">
            <input id="audit-search" name="search" value="{{ $search }}" placeholder="Search action, user, or model" class="min-w-0 flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            <select id="audit-action" name="action" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"><option value="">All actions</option>@foreach($actions as $item)<option value="{{ $item }}" @selected($action === $item)>{{ $item }}</option>@endforeach</select>
            <input name="from" type="date" value="{{ $dateFrom?->format('Y-m-d') }}" aria-label="From date" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            <input name="to" type="date" value="{{ $dateTo?->format('Y-m-d') }}" aria-label="To date" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
        </form>
        <div id="audit-results" class="p-4 lg:p-6">
            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800"><table class="min-w-full text-left text-sm"><thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-800/50"><tr><th class="px-4 py-3">Time</th><th class="px-4 py-3">Action</th><th class="px-4 py-3">User</th><th class="px-4 py-3">Resource</th><th class="px-4 py-3 text-right">Details</th></tr></thead><tbody id="audit-table-body" class="divide-y divide-gray-100 dark:divide-gray-800">@include('admin.audit-logs._results')</tbody></table></div>
            <div id="audit-pagination" class="mt-4">{{ $logs->links() }}</div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('audit-filters');
    const results = document.getElementById('audit-results');
    const fields = form.querySelectorAll('input, select');
    let timer;
    let request;
    const load = (url) => {
        request?.abort(); request = new AbortController();
        fetch(url, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, signal: request.signal })
            .then(response => response.json())
            .then(data => { results.querySelector('#audit-table-body').innerHTML = data.html; results.querySelector('#audit-pagination').innerHTML = data.pagination; window.history.replaceState({}, '', url); });
    };
    const refresh = () => { const url = new URL(form.action); new FormData(form).forEach((value, key) => value && url.searchParams.set(key, value)); url.searchParams.delete('page'); load(url); };
    fields.forEach(field => field.addEventListener(field.tagName === 'INPUT' && field.type === 'text' ? 'input' : 'change', () => { clearTimeout(timer); timer = setTimeout(refresh, 250); }));
    form.addEventListener('submit', event => { event.preventDefault(); refresh(); });
    results.addEventListener('click', event => { const link = event.target.closest('a[href*="page="]'); if (!link) return; event.preventDefault(); load(link.href); });
})();
</script>
@endpush