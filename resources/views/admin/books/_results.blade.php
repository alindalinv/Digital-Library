@php
    $statusStyles = [
        'draft' => [
            'label' => 'Draft',
            'class' => 'inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300',
            'dot'   => 'bg-gray-400',
        ],
        'published' => [
            'label' => 'Published',
            'class' => 'inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400',
            'dot'   => 'bg-green-500',
        ],
        'archived' => [
            'label' => 'Archived',
            'class' => 'inline-flex items-center gap-1.5 rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
            'dot'   => 'bg-yellow-500',
        ],
    ];
@endphp

@if ($books->isEmpty())
    {{-- Empty State --}}
    <div class="flex flex-col items-center justify-center py-16 text-center">
        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>

        <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
            No books found
        </h3>

        <p class="mt-1 max-w-sm text-sm text-gray-500 dark:text-gray-400">
            @if (!empty($search))
                No books match "<strong>{{ $search }}</strong>".
                @if (!empty($featured)) with the featured filter applied. @endif
                Try a different search term.
            @elseif (!empty($featured))
                No <strong>featured</strong> books found
                @if (!empty($status) && $status !== 'all')
                    with status <strong>{{ $status }}</strong>
                @endif
                .
            @elseif (!empty($status) && $status !== 'all')
                There are no <strong>{{ $status }}</strong> books yet.
            @else
                Get started by adding your first book to the catalog.
            @endif
        </p>

        @if (empty($search) && (empty($status) || $status === 'all'))
            <a href="{{ route('admin.books.create') }}"
               class="mt-4 inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Book
            </a>
        @endif
    </div>
@else
    {{-- Results count --}}
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            <span class="mr-1 inline-block h-1.5 w-1.5 rounded-full bg-blue-500 align-middle"></span>
            Results
            <span class="mx-1 text-gray-300 dark:text-gray-700">/</span>
            Showing
            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $books->firstItem() }}</span>
            to
            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $books->lastItem() }}</span>
            of
            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $books->total() }}</span>
            books
        </p>
    </div>

    {{-- Books Table --}}
    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm dark:border-gray-800">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/50">
                <tr class="text-xs uppercase tracking-wide">
                    <th scope="col" class="px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Book</th>
                    <th scope="col" class="hidden px-4 py-3 font-medium text-gray-600 md:table-cell dark:text-gray-400">Category</th>
                    <th scope="col" class="hidden px-4 py-3 font-medium text-gray-600 lg:table-cell dark:text-gray-400">Authors</th>
                    <th scope="col" class="hidden px-4 py-3 font-medium text-gray-600 md:table-cell dark:text-gray-400">Price</th>
                    <th scope="col" class="hidden px-4 py-3 font-medium text-gray-600 md:table-cell dark:text-gray-400">Stock</th>
                    <th scope="col" class="px-4 py-3 font-medium text-gray-600 dark:text-gray-400">Status</th>
                    <th scope="col" class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-400">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @foreach ($books as $book)
                    @php
                        $bookStatus = $statusStyles[$book->status] ?? $statusStyles['draft'];
                    @endphp

                    <tr class="transition hover:bg-blue-50/40 dark:hover:bg-blue-500/[0.04]">

                        {{-- Book: cover + title --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if ($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}"
                                         alt="{{ $book->title }}"
                                         class="h-12 w-9 shrink-0 rounded border border-gray-200 object-cover dark:border-gray-700">
                                @else
                                    <div class="flex h-12 w-9 shrink-0 items-center justify-center rounded border border-dashed border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif

                                <div class="min-w-0">
                                    <a href="{{ route('admin.books.show', $book) }}"
                                       class="block truncate font-medium text-gray-800 hover:text-blue-600 dark:text-white/90 dark:hover:text-blue-400">
                                        {{ $book->title }}
                                    </a>

                                    @if ($book->isbn)
                                        <p class="mt-0.5 truncate text-xs text-gray-500 dark:text-gray-400">
                                            ISBN: {{ $book->isbn }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Category --}}
                        <td class="hidden px-4 py-3 md:table-cell">
                            @if ($book->category)
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $book->category->name }}
                                </span>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">—</span>
                            @endif
                        </td>

                        {{-- Authors --}}
                        <td class="hidden px-4 py-3 lg:table-cell">
                            @if ($book->authors->isNotEmpty())
                                <span class="text-gray-700 dark:text-gray-300">
                                    {{ $book->authors->take(2)->pluck('name')->join(', ') }}
                                </span>
                                @if ($book->authors->count() > 2)
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        +{{ $book->authors->count() - 2 }} more
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400 dark:text-gray-500">—</span>
                            @endif
                        </td>

                        {{-- Price --}}
                        <td class="hidden px-4 py-3 md:table-cell">
                            @if ($book->price !== null)
                                <span class="font-medium text-gray-800 dark:text-gray-200">
                                    ${{ number_format($book->price, 2) }}
                                </span>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">—</span>
                            @endif
                        </td>

                        {{-- Stock --}}
                        <td class="hidden px-4 py-3 md:table-cell">
                            @if ($book->stock <= 0)
                                <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                    Out
                                </span>
                            @elseif ($book->stock <= 5)
                                <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-0.5 text-xs font-medium text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    {{ $book->stock }} left
                                </span>
                            @else
                                <span class="text-gray-700 dark:text-gray-300">
                                    {{ number_format($book->stock) }}
                                </span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3">
                            <span class="{{ $bookStatus['class'] }}">
                                <span class="inline-block h-1.5 w-1.5 rounded-full {{ $bookStatus['dot'] }}"></span>
                                {{ $bookStatus['label'] }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">

                                {{-- View --}}
                                <a href="{{ route('admin.books.show', $book) }}"
                                   class="rounded-lg p-1.5 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                                   aria-label="View {{ $book->title }}"
                                   title="View">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('admin.books.edit', $book) }}"
                                   class="rounded-lg p-1.5 text-gray-500 transition hover:bg-gray-100 hover:text-blue-600 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-blue-400"
                                   aria-label="Edit {{ $book->title }}"
                                   title="Edit">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                              {{-- Delete --}}
                                <button
                                    type="button"
                                    data-delete-book
                                    data-book-id="{{ $book->id }}"
                                    data-book-title="{{ $book->title }}"
                                    data-redirect-url="{{ route('admin.books.trashed') }}"
                                    class="rounded-lg p-1.5 text-gray-500 transition hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-900/20 dark:hover:text-red-400"
                                    aria-label="Delete {{ $book->title }}"
                                    title="Move to trash"
                                >
                                    <svg
                                        class="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1v3M4 7h16"
                                        />
                                    </svg>
                                </button>

                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    {{-- Pagination --}}
    @if ($books->hasPages())
        <div class="mt-5">
            {{ $books->links() }}
        </div>
    @endif
@endif