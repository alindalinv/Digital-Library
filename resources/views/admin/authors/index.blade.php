@extends('layouts.admin.app')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                Authors
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage book authors in your digital library.
            </p>
        </div>

        @can('authors.create', 'admin')
            <a
                href="{{ route('admin.authors.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-medium text-white transition hover:bg-primary/90"
            >
                <i class="fas fa-plus"></i>
                Add Author
            </a>
        @endcan

    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-900/50 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    {{-- Search --}}
    <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">

        <form
            method="GET"
            action="{{ route('admin.authors.index') }}"
            class="flex flex-col gap-3 sm:flex-row"
        >

            <div class="relative flex-1">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                </span>

                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search authors..."
                    class="w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-10 pr-4 text-sm text-gray-800 outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500"
                >
            </div>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5"
            >
                <i class="fas fa-filter"></i>
                Search
            </button>

            @if($search !== '')
                <a
                    href="{{ route('admin.authors.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5"
                >
                    Clear
                </a>
            @endif

        </form>

    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[800px]">

                <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-800 dark:bg-white/[0.02]">

                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Author
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Biography
                        </th>

                        <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Books
                        </th>

                        <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Actions
                        </th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                    @forelse($authors as $author)

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                            {{-- Author --}}
                            <td class="px-5 py-4">

                                <a
                                    href="{{ route('admin.authors.show', $author) }}"
                                    class="font-medium text-gray-800 hover:text-primary dark:text-white/90 dark:hover:text-primary"
                                >
                                    {{ $author->name }}
                                </a>

                                <p class="mt-1 text-xs text-gray-400">
                                    #{{ $author->id }}
                                </p>

                            </td>

                            {{-- Biography --}}
                            <td class="max-w-md px-5 py-4">

                                @if($author->bio)
                                    <p class="line-clamp-2 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $author->bio }}
                                    </p>
                                @else
                                    <span class="text-sm italic text-gray-400">
                                        No biography
                                    </span>
                                @endif

                            </td>

                            {{-- Books --}}
                            <td class="px-5 py-4 text-center">

                                <span class="inline-flex min-w-8 items-center justify-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                    {{ $author->books_count }}
                                </span>

                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    @can('authors.view', 'admin')
                                        <a
                                            href="{{ route('admin.authors.show', $author) }}"
                                            title="View"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-primary dark:text-gray-400 dark:hover:bg-white/5"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan

                                    @can('authors.update', 'admin')
                                        <a
                                            href="{{ route('admin.authors.edit', $author) }}"
                                            title="Edit"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-primary dark:text-gray-400 dark:hover:bg-white/5"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('authors.delete', 'admin')
                                        <button
                                            type="button"
                                            data-delete-author
                                            data-author-id="{{ $author->id }}"
                                            data-author-name="{{ $author->name }}"
                                            title="Delete"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-900/20 dark:hover:text-red-400"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800">
                                        <i class="fas fa-user-edit text-xl"></i>
                                    </div>

                                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                        No authors found
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        @if($search)
                                            Try changing your search.
                                        @else
                                            Start by creating your first author.
                                        @endif
                                    </p>

                                    @if($search)
                                        <a
                                            href="{{ route('admin.authors.index') }}"
                                            class="mt-4 text-sm font-medium text-primary hover:underline"
                                        >
                                            Clear search
                                        </a>
                                    @endif

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if($authors->hasPages())

            <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-800">
                {{ $authors->links() }}
            </div>

        @endif

    </div>

</div>

{{-- Delete form --}}
<form
    id="delete-author-form"
    method="POST"
    class="hidden"
>
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', () => {

    const deleteForm = document.getElementById('delete-author-form');

    document.querySelectorAll('[data-delete-author]').forEach((button) => {

        button.addEventListener('click', async () => {

            const authorId = button.dataset.authorId;
            const authorName = button.dataset.authorName;

            if (!authorId) {
                return;
            }

            const result = await Swal.fire({
                title: 'Delete author?',
                text: `Are you sure you want to delete "${authorName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true,
            });

            if (!result.isConfirmed) {
                return;
            }

            deleteForm.action =
                `{{ url('/admin/authors') }}/${authorId}`;

            deleteForm.submit();
        });

    });

});
</script>

@endpush