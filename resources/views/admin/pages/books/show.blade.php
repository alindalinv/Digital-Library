@extends('layouts.admin.app')

@section('content')

    @php
        // Status badge styles keyed by status value.
        $statusStyles = [
            'draft' => [
                'label' => 'Draft',
                'class' => 'inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                'dot' => 'bg-gray-400',
            ],
            'published' => [
                'label' => 'Published',
                'class' => 'inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:bg-green-900/30 dark:text-green-400',
                'dot' => 'bg-green-500',
            ],
            'archived' => [
                'label' => 'Archived',
                'class' => 'inline-flex items-center gap-1.5 rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                'dot' => 'bg-yellow-500',
            ],
        ];

        $status = $statusStyles[$book->status] ?? $statusStyles['draft'];

        // Stock visual state.
        $stockClass = match (true) {
            $book->stock <= 0 => 'text-red-600 dark:text-red-400 font-semibold',
            $book->stock <= 5 => 'text-yellow-600 dark:text-yellow-400 font-semibold',
            default => 'text-gray-800 dark:text-gray-200',
        };

        $stockLabel = match (true) {
            $book->stock <= 0 => 'Out of stock',
            $book->stock <= 5 => $book->stock . ' (Low stock)',
            default => number_format($book->stock) . ' in stock',
        };
    @endphp

    <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-gray-900">

        {{-- =========================================================
        Header
        ========================================================== --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Book Details
                </h3>

                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    View complete information about "{{ $book->title }}".
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.books.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>

                    Back to Books
                </a>

                <a href="{{ route('admin.books.edit', $book) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-brand-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>

                    Edit Book
                </a>
            </div>
        </div>


        {{-- =========================================================
        Body
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- =================================================
            LEFT COLUMN
            ================================================== --}}
            <div class="space-y-5 lg:col-span-2">

                {{-- Title + Status --}}
                <div class="rounded-xl border border-gray-200 p-5 dark:border-gray-800">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                        <div class="min-w-0">
                            <h1 class="break-words text-xl font-bold text-gray-800 dark:text-white/90">
                                {{ $book->title }}
                            </h1>

                            @if ($book->slug)
                                <p class="mt-1 truncate font-mono text-xs text-gray-500 dark:text-gray-400">
                                    /{{ $book->slug }}
                                </p>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <span class="{{ $status['class'] }}">
                                <span class="inline-block h-1.5 w-1.5 rounded-full {{ $status['dot'] }}"></span>
                                {{ $status['label'] }}
                            </span>

                            @if ($book->is_featured)
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L1.078 10.1c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                    Featured
                                </span>
                            @endif
                        </div>

                    </div>
                </div>


                {{-- Description --}}
                @if ($book->description)
                    <div class="rounded-xl border border-gray-200 p-5 dark:border-gray-800">
                        <h4 class="mb-3 text-sm font-semibold text-gray-800 dark:text-white/90">
                            Description
                        </h4>

                        <p class="whitespace-pre-line text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                            {!! $book->description !!}
                        </p>
                    </div>
                @endif


                {{-- Book Information --}}
                <div class="rounded-xl border border-gray-200 p-5 dark:border-gray-800">
                    <h4 class="mb-4 text-sm font-semibold text-gray-800 dark:text-white/90">
                        Book Information
                    </h4>

                    <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">

                        {{-- ISBN --}}
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                ISBN
                            </dt>
                            <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">
                                {{ $book->isbn ?: '—' }}
                            </dd>
                        </div>

                        {{-- Category --}}
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Category
                            </dt>
                            <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">
                                @if ($book->category)
                                    <span
                                        class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ $book->category->name }}
                                    </span>
                                @else
                                    —
                                @endif
                            </dd>
                        </div>

                        {{-- Publisher --}}
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Publisher
                            </dt>
                            <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">
                                {{ $book->publisher?->name ?? '—' }}
                            </dd>
                        </div>

                        {{-- Published Year --}}
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Published Year
                            </dt>
                            <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">
                                {{ $book->published_year ?: '—' }}
                            </dd>
                        </div>

                        {{-- Language --}}
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Language
                            </dt>
                            <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">
                                {{ $book->language ?: '—' }}
                            </dd>
                        </div>

                        {{-- Pages --}}
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Pages
                            </dt>
                            <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">
                                {{ $book->pages ? number_format($book->pages) : '—' }}
                            </dd>
                        </div>

                        {{-- Price --}}
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Price
                            </dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                @if ($book->price !== null)
                                    ${{ number_format($book->price, 2) }}
                                @else
                                    —
                                @endif
                            </dd>
                        </div>

                        {{-- Stock --}}
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Stock
                            </dt>
                            <dd class="mt-1 text-sm {{ $stockClass }}">
                                {{ $stockLabel }}
                            </dd>
                        </div>

                    </dl>
                </div>


                {{-- Authors --}}
                <div class="rounded-xl border border-gray-200 p-5 dark:border-gray-800">
                    <h4 class="mb-4 text-sm font-semibold text-gray-800 dark:text-white/90">
                        Authors
                        <span class="ml-1 text-xs font-normal text-gray-500 dark:text-gray-400">
                            ({{ $book->authors->count() }})
                        </span>
                    </h4>

                    @if ($book->authors->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach ($book->authors as $author)
                                <span
                                    class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">

                                    <svg class="h-3.5 w-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>

                                    {{ $author->name }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            No authors assigned.
                        </p>
                    @endif
                </div>

            </div>


            {{-- =================================================
            RIGHT COLUMN
            ================================================== --}}
            <div class="space-y-5">

                {{-- Cover Image --}}
                <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                    <h4 class="mb-3 text-sm font-semibold text-gray-800 dark:text-white/90">
                        Cover Image
                    </h4>

                    <div class="flex justify-center">
                        @if ($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}"
                                class="h-64 w-44 rounded-lg border border-gray-200 object-cover shadow-sm dark:border-gray-700">
                        @else
                            <div
                                class="flex h-64 w-44 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 text-center dark:border-gray-700 dark:bg-gray-800">
                                <div class="px-4">
                                    <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>

                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        No cover image
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>


                {{-- E-book Files --}}
                <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-white/90">E-book Files</h4>
                        <a href="{{ route('admin.ebook-files.create', ['book_id' => $book->id]) }}" class="text-xs font-medium text-brand-500 hover:text-brand-600">Add file</a>
                    </div>
                    @forelse ($book->files as $file)
                        <div class="flex items-center justify-between gap-3 border-t border-gray-100 py-2 first:border-0 first:pt-0 dark:border-gray-800">
                            <div><p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ strtoupper($file->file_type) }} @if($file->is_primary)<span class="ml-1 text-xs text-green-600">Primary</span>@endif</p><p class="text-xs text-gray-500">{{ number_format(($file->file_size ?? 0) / 1048576, 2) }} MB</p></div>
                            <a href="{{ route('admin.ebook-files.show', $file) }}" class="text-xs text-brand-500 hover:text-brand-600">Open</a>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">No e-book files yet.</p>
                    @endforelse
                </div>

                {{-- Metadata --}}
                <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                    <h4 class="mb-4 text-sm font-semibold text-gray-800 dark:text-white/90">
                        Metadata
                    </h4>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-gray-500 dark:text-gray-400">Book ID</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">
                                #{{ $book->id }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-3">
                            <span class="text-gray-500 dark:text-gray-400">Created</span>
                            <span class="text-right text-gray-800 dark:text-gray-200">
                                {{ $book->created_at?->format('M d, Y H:i') ?? '—' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-3">
                            <span class="text-gray-500 dark:text-gray-400">Last Updated</span>
                            <span class="text-right text-gray-800 dark:text-gray-200">
                                {{ $book->updated_at?->format('M d, Y H:i') ?? '—' }}
                            </span>
                        </div>

                        @if ($book->deleted_at)
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-gray-500 dark:text-gray-400">Deleted</span>
                                <span class="text-right text-red-600 dark:text-red-400">
                                    {{ $book->deleted_at->format('M d, Y H:i') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>


                {{-- Danger Zone --}}
                <div class="rounded-xl border border-red-200 p-4 dark:border-red-900/50">
                    <h4 class="mb-3 text-sm font-semibold text-red-700 dark:text-red-400">
                        Danger Zone
                    </h4>

                    <p class="mb-3 text-xs text-gray-500 dark:text-gray-400">
                        Moving a book to trash is reversible. It won't be permanently deleted until you empty the trash.
                    </p>

                    <form method="POST" action="{{ route('admin.books.destroy', $book) }}"
                        onsubmit="return confirm('Move this book to trash?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:border-red-900/50 dark:bg-transparent dark:text-red-400 dark:hover:bg-red-900/20">

                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>

                            Move to Trash
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>
@endsection
