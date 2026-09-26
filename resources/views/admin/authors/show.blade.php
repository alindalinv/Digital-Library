@extends('layouts.admin.app')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <a
                    href="{{ route('admin.authors.index') }}"
                    class="hover:text-primary"
                >
                    Authors
                </a>

                <i class="fas fa-chevron-right text-[10px]"></i>

                <span>{{ $author->name }}</span>
            </div>

            <h1 class="mt-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                {{ $author->name }}
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Author details and associated books.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">

            @can('authors.update', 'admin')
                <a
                    href="{{ route('admin.authors.edit', $author) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-medium text-white transition hover:bg-primary/90"
                >
                    <i class="fas fa-edit"></i>
                    Edit
                </a>
            @endcan

            <a
                href="{{ route('admin.authors.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5"
            >
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

        </div>

    </div>

    {{-- Author information --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Main information --}}
        <div class="lg:col-span-2">

            <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

                <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-800">
                    <h2 class="text-base font-semibold text-gray-800 dark:text-white/90">
                        Biography
                    </h2>
                </div>

                <div class="p-6">

                    @if($author->bio)

                        <div class="prose prose-sm max-w-none text-gray-600 dark:prose-invert dark:text-gray-400">
                            {!! nl2br(e($author->bio)) !!}
                        </div>

                    @else

                        <p class="text-sm italic text-gray-400">
                            No biography has been added for this author.
                        </p>

                    @endif

                </div>

            </div>

        </div>

        {{-- Statistics --}}
        <div class="space-y-6">

            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Author ID
                </p>

                <p class="mt-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    #{{ $author->id }}
                </p>

            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Books
                </p>

                <p class="mt-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    {{ $author->books->count() }}
                </p>

            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Created
                </p>

                <p class="mt-2 text-sm font-medium text-gray-800 dark:text-white/90">
                    {{ $author->created_at?->format('M d, Y') ?? '—' }}
                </p>

            </div>

        </div>

    </div>

    {{-- Books --}}
    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5 dark:border-gray-800">

            <div>
                <h2 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Books by {{ $author->name }}
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $author->books->count() }} {{ str('book')->plural($author->books->count()) }}
                </p>
            </div>

        </div>

        <div class="overflow-x-auto">

            @if($author->books->isNotEmpty())

                <table class="w-full min-w-[700px]">

                    <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-white/[0.02]">

                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Book
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Added
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Action
                            </th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                        @foreach($author->books as $book)

                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-800 dark:text-white/90">
                                        {{ $book->title }}
                                    </div>

                                </td>

                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $book->created_at?->format('M d, Y') ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-right">

                                    @can('books.view', 'admin')
                                        <a
                                            href="{{ route('admin.books.show', $book) }}"
                                            class="text-sm font-medium text-primary hover:underline"
                                        >
                                            View
                                        </a>
                                    @endcan

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="px-6 py-14 text-center">

                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800">
                        <i class="fas fa-book"></i>
                    </div>

                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        No books assigned
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        This author is not currently associated with any books.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection