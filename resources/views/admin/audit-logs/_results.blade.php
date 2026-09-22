@forelse ($logs as $log)
    <tr class="align-top hover:bg-gray-50 dark:hover:bg-gray-800/40">
        <td class="whitespace-nowrap px-4 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $log->created_at->format('Y-m-d H:i') }}</td>
        <td class="px-4 py-3"><span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">{{ $log->action }}</span></td>
        <td class="px-4 py-3"><p class="font-medium text-gray-800 dark:text-gray-200">{{ $log->user?->name ?? 'System' }}</p><p class="text-xs text-gray-500">{{ $log->user?->email ?? 'Automated action' }}</p></td>
        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ class_basename($log->model_type ?? '—') }} @if($log->model_id)<span class="text-xs text-gray-400">#{{ $log->model_id }}</span>@endif</td>
        <td class="px-4 py-3 text-right">
            <details class="group inline-block text-left">
                <summary class="cursor-pointer list-none rounded-lg px-2 py-1 text-xs font-medium text-brand-600 hover:bg-brand-50 dark:text-brand-400 dark:hover:bg-brand-500/10">View changes</summary>
                <div class="absolute z-10 mt-2 w-80 max-w-[calc(100vw-3rem)] rounded-xl border border-gray-200 bg-white p-3 text-left shadow-xl dark:border-gray-700 dark:bg-gray-900">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Old values</p>
                    <pre class="max-h-32 overflow-auto rounded-lg bg-gray-50 p-2 text-xs text-gray-600 dark:bg-gray-800 dark:text-gray-300">{{ json_encode($log->old_values ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    <p class="mb-2 mt-3 text-xs font-semibold uppercase tracking-wide text-gray-500">New values</p>
                    <pre class="max-h-32 overflow-auto rounded-lg bg-gray-50 p-2 text-xs text-gray-600 dark:bg-gray-800 dark:text-gray-300">{{ json_encode($log->new_values ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </div>
            </details>
        </td>
    </tr>
@empty
    <tr><td colspan="5" class="px-4 py-14 text-center text-sm text-gray-500">No audit logs found.</td></tr>
@endforelse