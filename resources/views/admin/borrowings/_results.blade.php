@forelse ($borrowings as $borrowing)
    @php
        $isOverdue = $borrowing->due_at && $borrowing->due_at->isPast() && !$borrowing->returned_at;
        $statusClass = match ($borrowing->status) {
            'approved' => 'bg-green-100 text-green-700',
            'rejected' => 'bg-red-100 text-red-700',
            'returned' => 'bg-gray-100 text-gray-600',
            'overdue' => 'bg-red-100 text-red-700',
            default => 'bg-yellow-100 text-yellow-700',
        };
    @endphp
    <tr class="text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-800/40">
        <td class="px-4 py-3">
            <div class="flex items-center gap-3">
                @if ($borrowing->book->cover_url)
                    <img src="{{ $borrowing->book->cover_url }}" alt="{{ $borrowing->book->title }}"
                        class="h-12 w-9 rounded object-cover">
                @endif<div>
                    <a href="{{ route('admin.books.show', $borrowing->book) }}"
                        class="font-medium text-gray-800 hover:text-brand-500 dark:text-white">{{ $borrowing->book->title }}</a>
                    <p class="text-xs text-gray-500">#{{ $borrowing->getKey() }}</p>
                </div>
            </div>
        </td>
        <td class="px-4 py-3">
            <p class="font-medium">{{ $borrowing->user?->name ?? 'Unknown user' }}</p>
            <p class="text-xs text-gray-500">{{ $borrowing->user?->email }}</p>
        </td>
        <td class="px-4 py-3"><span
                class="rounded-full px-2.5 py-1 text-xs font-medium {{ $isOverdue ? 'bg-red-100 text-red-700' : $statusClass }}">{{ $isOverdue ? 'Overdue' : ucfirst($borrowing->status) }}</span>
        </td>
        <td class="px-4 py-3 text-xs text-gray-500">{{ $borrowing->borrowed_at?->format('M d, Y') ?? '—' }}</td>
        <td class="px-4 py-3 text-xs text-gray-500">
            {{ $borrowing->returned_at ? 'Returned' : ($borrowing->due_at?->format('M d, Y') ?? '—') }}
        </td>
        <td class="px-4 py-3 text-right">@if($borrowing->status === 'pending')
            <form method="POST" action="{{ route('admin.borrowings.approve', $borrowing) }}" class="inline">@csrf<button
                    class="rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600">Approve</button>
        </form>@else<span class="text-xs text-gray-400">No action</span>@endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-4 py-14 text-center text-sm text-gray-500">No borrowings found.</td>
    </tr>
@endforelse