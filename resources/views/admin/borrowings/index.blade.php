@extends('layouts.admin.app')

@section('content')
        <div class="flex flex-col gap-4 border-b border-gray-200 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">
            <div><p class="mb-1 text-xs font-semibold uppercase tracking-wide text-brand-500">Circulation</p><h1 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $forcedStatus === 'pending' ? 'Pending Approvals' : 'Borrowings' }}</h1><p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Review member borrowing requests and due dates.</p></div>
            <div class="flex gap-2"><a href="{{ route('admin.borrowings.index') }}" class="rounded-lg border px-3 py-2 text-sm {{ ! $forcedStatus ? 'border-brand-300 bg-brand-50 text-brand-700' : 'border-gray-300 text-gray-600' }}">All borrowings</a><a href="{{ route('admin.borrowings.pending') }}" class="rounded-lg border px-3 py-2 text-sm {{ $forcedStatus === 'pending' ? 'border-yellow-300 bg-yellow-50 text-yellow-700' : 'border-gray-300 text-gray-600' }}">Pending <span class="ml-1 rounded-full bg-yellow-100 px-1.5 py-0.5 text-xs">{{ $pendingCount }}</span></a></div>
        </div>
        <form id="borrowing-filters" action="{{ $forcedStatus === 'pending' ? route('admin.borrowings.pending') : route('admin.borrowings.index') }}" class="flex flex-col gap-3 border-b border-gray-200 bg-gray-50/70 p-4 sm:flex-row dark:border-gray-800 dark:bg-gray-800/30"><input name="search" value="{{ $search }}" placeholder="Search member or book" class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white"><select name="status" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white" @disabled($forcedStatus === 'pending')><option value="all">All statuses</option>@foreach(['pending','approved','rejected','returned','overdue'] as $item)<option value="{{ $item }}" @selected($status === $item)>{{ ucfirst($item) }}</option>@endforeach</select></form>
        <div id="borrowing-results"><div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800"><table class="min-w-full text-left"><thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-800/50"><tr><th class="px-4 py-3">Book</th><th class="px-4 py-3">Member</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Borrowed</th><th class="px-4 py-3">Due</th><th class="px-4 py-3 text-right">Action</th></tr></thead><tbody id="borrowing-table" class="divide-y divide-gray-100 dark:divide-gray-800">@include('admin.borrowings._results')</tbody></table></div><div id="borrowing-pagination" class="mt-4">{{ $borrowings->links() }}</div></div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('borrowing-filters');
    const results = document.getElementById('borrowing-results');
    if (!form || !results) return;
    const search = form.querySelector('[name="search"]');
    const status = form.querySelector('[name="status"]');
    let timer;
    let request;
    const load = url => { request?.abort(); request = new AbortController(); fetch(url, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, signal: request.signal }).then(response => response.json()).then(data => { results.querySelector('#borrowing-table').innerHTML = data.html; results.querySelector('#borrowing-pagination').innerHTML = data.pagination; window.history.replaceState({}, '', url); }).catch(error => { if (error.name !== 'AbortError') console.error('Borrowings failed.', error); }); };
    const refresh = () => { const url = new URL(form.action); if (search.value.trim()) url.searchParams.set('search', search.value.trim()); else url.searchParams.delete('search'); if (status && status.value !== 'all' && !status.disabled) url.searchParams.set('status', status.value); else url.searchParams.delete('status'); url.searchParams.delete('page'); load(url); };
    search.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(refresh, 300); });
    status?.addEventListener('change', refresh);
    results.addEventListener('click', event => { const link = event.target.closest('a[href*="page="]'); if (!link) return; event.preventDefault(); load(link.href); });
})();
</script>
@endpush
