@extends('layouts.admin.app')

@section('content')
    <x-admin.common.page-breadcrumb pageTitle="Books" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-gray-900">

        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Books ({{ $books->total() }})
            </h3>

            <div class="flex gap-2">
                <form method="GET">
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Search books..."
                           class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                </form>

                <a href="{{ route('admin.books.create') }}"
                   class="rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    + New Book
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-xs uppercase text-gray-500 dark:border-gray-800">
                        <th class="pb-3">Title</th>
                        <th class="pb-3">Author</th>
                        <th class="pb-3">Category</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <td class="py-3 font-medium text-gray-800 dark:text-white/90">
                                {{ $book->title }}
                            </td>
                            <td class="py-3 text-gray-600 dark:text-gray-400">
                                {{ $book->authors->pluck('name')->join(', ') ?: '—' }}
                            </td>
                            <td class="py-3 text-gray-600 dark:text-gray-400">
                                {{ $book->category->name ?? '—' }}
                            </td>
                            <td class="py-3">
                                <span class="rounded-full bg-green-50 px-2 py-0.5 text-xs text-green-700 dark:bg-green-500/15 dark:text-green-400">
                                    {{ $book->status }}
                                </span>
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('admin.books.edit', $book) }}"
                                   class="text-blue-600 hover:underline dark:text-blue-400">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500 dark:text-gray-400">
                                No books found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($books->hasPages())
            <div class="mt-4">{{ $books->links() }}</div>
        @endif

    </div>
@endsection