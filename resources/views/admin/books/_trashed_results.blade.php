@if ($books->isEmpty())
    {{-- =========================================================
         Empty State
    ========================================================== --}}
    <div class="flex flex-col items-center justify-center py-16 text-center">

        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>

        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
            Trash is empty
        </h3>

        <p class="mt-1 max-w-sm text-sm text-gray-500 dark:text-gray-400">
            @if (!empty($search))
                No trashed books match "<strong>{{ $search }}</strong>".
                Try a different search term.
            @else
                Books you delete will appear here. They can be restored or permanently removed.
            @endif
        </p>

        @if (empty($search))
            <a href="{{ route('admin.books.index') }}"
               class="mt-4 inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">

                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>

                Back to Books
            </a>
        @endif
    </div>
@else
    {{-- =========================================================
         Results count
    ========================================================== --}}
    <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Showing
            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $books->firstItem() }}</span>
            to
            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $books->lastItem() }}</span>
            of
            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $books->total() }}</span>
            trashed {{ Str::plural('book', $books->total()) }}
        </p>

        @if (!empty($search))
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Filtered by: <span class="font-medium text-gray-700 dark:text-gray-300">"{{ $search }}"</span>
            </p>
        @endif
    </div>


    {{-- =========================================================
         Trashed Books Table
    ========================================================== --}}
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/50">
                <tr>
                    <th scope="col" class="px-4 py-3 font-medium text-gray-600 dark:text-gray-400">
                        Book
                    </th>

                    <th scope="col" class="hidden px-4 py-3 font-medium text-gray-600 md:table-cell dark:text-gray-400">
                        Category
                    </th>

                    <th scope="col" class="hidden px-4 py-3 font-medium text-gray-600 lg:table-cell dark:text-gray-400">
                        Authors
                    </th>

                    <th scope="col" class="hidden px-4 py-3 font-medium text-gray-600 sm:table-cell dark:text-gray-400">
                        Deleted
                    </th>

                    <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-400">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @foreach ($books as $book)
                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">

                        {{-- ====================================
                             Book (cover + title + ISBN)
                        ===================================== --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">

                                {{-- Cover --}}
                                @if ($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}"
                                         alt="{{ $book->title }}"
                                         loading="lazy"
                                         class="h-12 w-9 shrink-0 rounded border border-gray-200 object-cover opacity-60 grayscale transition group-hover:opacity-100 group-hover:grayscale-0 dark:border-gray-700">
                                @else
                                    <div class="flex h-12 w-9 shrink-0 items-center justify-center rounded border border-dashed border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Title + ISBN --}}
                                <div class="min-w-0">
                                    <span class="block truncate font-medium text-gray-700 dark:text-gray-300"
                                          title="{{ $book->title }}">
                                        {{ $book->title }}
                                    </span>

                                    @if ($book->isbn)
                                        <p class="mt-0.5 truncate text-xs text-gray-500 dark:text-gray-400">
                                            ISBN: {{ $book->isbn }}
                                        </p>
                                    @endif
                                </div>

                            </div>
                        </td>


                        {{-- ====================================
                             Category
                        ===================================== --}}
                        <td class="hidden px-4 py-3 md:table-cell">
                            @if ($book->category)
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $book->category->name }}
                                </span>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">—</span>
                            @endif
                        </td>


                        {{-- ====================================
                             Authors
                        ===================================== --}}
                        <td class="hidden px-4 py-3 lg:table-cell">
                            @if ($book->authors->isNotEmpty())
                                <span class="text-gray-700 dark:text-gray-300">
                                    {{ $book->authors->take(2)->pluck('name')->join(', ') }}
                                </span>

                                @if ($book->authors->count() > 2)
                                    <span class="ml-1 text-xs text-gray-500 dark:text-gray-400">
                                        +{{ $book->authors->count() - 2 }} more
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400 dark:text-gray-500">—</span>
                            @endif
                        </td>


                        {{-- ====================================
                             Deleted At
                        ===================================== --}}
                        <td class="hidden px-4 py-3 sm:table-cell">
                            @if ($book->deleted_at)
                                <span class="text-xs text-gray-500 dark:text-gray-400"
                                      title="{{ $book->deleted_at->format('M d, Y H:i') }}">
                                    {{ $book->deleted_at->diffForHumans() }}
                                </span>
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $book->deleted_at->format('M d, Y') }}
                                </p>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">—</span>
                            @endif
                        </td>


                        {{-- ====================================
                             Actions
                        ===================================== --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">

                                {{-- Restore --}}
                                <button type="button"
                                        data-restore-book
                                        data-book-id="{{ $book->id }}"
                                        data-book-title="{{ $book->title }}"
                                        class="rounded-lg p-1.5 text-gray-500 transition hover:bg-green-50 hover:text-green-600 focus:outline-none focus:ring-2 focus:ring-green-500/30 dark:text-gray-400 dark:hover:bg-green-900/20 dark:hover:text-green-400"
                                        aria-label="Restore {{ $book->title }}"
                                        title="Restore">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>

                                {{-- Force Delete --}}
                                <button type="button"
                                        data-force-delete-book
                                        data-book-id="{{ $book->id }}"
                                        data-book-title="{{ $book->title }}"
                                        class="rounded-lg p-1.5 text-gray-500 transition hover:bg-red-50 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500/30 dark:text-gray-400 dark:hover:bg-red-900/20 dark:hover:text-red-400"
                                        aria-label="Permanently delete {{ $book->title }}"
                                        title="Delete permanently">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>

                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    {{-- =========================================================
         Pagination
    ========================================================== --}}
    @if ($books->hasPages())
        <div class="mt-5">
            {{ $books->links() }}
        </div>
    @endif
@endif