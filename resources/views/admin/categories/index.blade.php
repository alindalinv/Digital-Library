@extends('layouts.admin.app')

@section('content')
    <x-admin.common.page-breadcrumb pageTitle="Categories" />
    <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-gray-900">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div><h3 class="text-lg font-semibold text-gray-800 dark:text-white">Categories</h3><p class="mt-1 text-sm text-gray-500">Organize books into clear groups.</p></div>
            @can('categories.create')<a href="{{ route('admin.categories.create') }}" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Add category</a>@endcan
        </div>

        @if (session('success'))<div class="mb-5 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-500/10 dark:text-green-400">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="mb-5 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-500/10 dark:text-red-400">{{ session('error') }}</div>@endif

        <form method="GET" class="mb-5"><input name="search" value="{{ $search }}" placeholder="Search categories" class="w-full max-w-md rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm dark:border-gray-700 dark:text-white"></form>
        <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800"><thead class="bg-gray-50 dark:bg-gray-800/50"><tr class="text-left text-xs font-medium uppercase tracking-wider text-gray-500"><th class="px-4 py-3">Category</th><th class="px-4 py-3">Parent</th><th class="px-4 py-3">Books</th><th class="px-4 py-3">Order</th><th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right">Actions</th></tr></thead><tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse ($categories as $category)
                <tr class="text-sm text-gray-700 dark:text-gray-300"><td class="px-4 py-3"><p class="font-medium">{{ $category->name }}</p><p class="max-w-xs truncate text-xs text-gray-500">{{ $category->description ?: 'No description' }}</p></td><td class="px-4 py-3">{{ $category->parent?->name ?: '—' }}</td><td class="px-4 py-3">{{ $category->books_count }}</td><td class="px-4 py-3">{{ $category->order }}</td><td class="px-4 py-3">@if($category->status)<span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-700">Active</span>@else<span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600">Inactive</span>@endif</td><td class="px-4 py-3 text-right whitespace-nowrap">@can('categories.update')<a href="{{ route('admin.categories.edit', $category) }}" class="text-brand-500 hover:text-brand-600">Edit</a>@endcan @can('categories.delete')<form class="ml-3 inline" method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600">Delete</button></form>@endcan</td></tr>
            @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500">No categories found.</td></tr>
            @endforelse
        </tbody></table></div>
        <div class="mt-5">{{ $categories->links() }}</div>
    </div>
@endsection
