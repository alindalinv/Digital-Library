<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
        <thead class="bg-gray-50 dark:bg-gray-800/50">
            <tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                <th class="px-4 py-3">Book</th><th class="px-4 py-3">Format</th><th class="px-4 py-3">Size</th><th class="px-4 py-3">Primary</th><th class="px-4 py-3">Updated</th><th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse ($ebookFiles as $ebookFile)
                <tr class="text-sm text-gray-700 dark:text-gray-300">
                    <td class="px-4 py-3"><a class="font-medium hover:text-brand-500" href="{{ route('admin.ebook-files.show', $ebookFile) }}">{{ $ebookFile->book->title }}</a><p class="text-xs text-gray-500">{{ $ebookFile->book->isbn ?: 'No ISBN' }}</p></td>
                    <td class="px-4 py-3 uppercase">{{ $ebookFile->file_type }}</td>
                    <td class="px-4 py-3">{{ $ebookFile->file_size ? number_format($ebookFile->file_size / 1048576, 2) . ' MB' : '—' }}</td>
                    <td class="px-4 py-3">@if ($ebookFile->is_primary)<span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-700">Primary</span>@else — @endif</td>
                    <td class="px-4 py-3">{{ $ebookFile->updated_at->format('M j, Y') }}</td>
                    <td class="px-4 py-3 text-right whitespace-nowrap"><a class="text-brand-500 hover:text-brand-600" href="{{ route('admin.ebook-files.show', $ebookFile) }}">View</a>@can('ebook-files.update') <a class="ml-3 text-brand-500 hover:text-brand-600" href="{{ route('admin.ebook-files.edit', $ebookFile) }}">Edit</a> @endcan @can('ebook-files.delete')<form class="ml-3 inline" method="POST" action="{{ route('admin.ebook-files.destroy', $ebookFile) }}" onsubmit="return confirm('Delete this e-book file permanently?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600">Delete</button></form>@endcan</td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500">No e-book files found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-5">{{ $ebookFiles->links() }}</div>
