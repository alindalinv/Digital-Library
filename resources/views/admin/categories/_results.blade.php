@if ($categories->isEmpty())
    <div class="px-4 py-10 text-center text-sm text-gray-500">No categories found.</div>
@else
    <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800"><thead class="bg-gray-50 dark:bg-gray-800/50"><tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500"><th class="px-4 py-3">Category</th><th class="px-4 py-3">Parent</th><th class="px-4 py-3">Books</th><th class="px-4 py-3">Order</th><th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right">Actions</th></tr></thead><tbody class="divide-y divide-gray-100 dark:divide-gray-800">
        @foreach ($categories as $category)
            <tr class="text-sm text-gray-700 dark:text-gray-300"><td class="px-4 py-3"><p class="font-medium">{{ $category->name }}</p><p class="max-w-xs truncate text-xs text-gray-500">{{ $category->description ?: 'No description' }}</p></td><td class="px-4 py-3">{{ $category->parent?->name ?: '—' }}</td><td class="px-4 py-3">{{ $category->books_count }}</td><td class="px-4 py-3">{{ $category->order }}</td><td class="px-4 py-3">@if($category->status)<span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-700">Active</span>@else<span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">Inactive</span>@endif</td><td class="whitespace-nowrap px-4 py-3 text-right">@can('categories.update')<a href="{{ route('admin.categories.edit', $category) }}" class="text-brand-500 hover:text-brand-600">Edit</a>@endcan @can('categories.delete')<form class="ml-3 inline" method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600">Delete</button></form>@endcan</td></tr>
        @endforeach
    </tbody></table></div>
@endif

<div class="mt-5">{{ $categories->links() }}</div>