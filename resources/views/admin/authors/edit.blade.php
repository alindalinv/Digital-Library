@extends('layouts.admin.app')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">

    {{-- Header --}}
    <div>
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
            <a
                href="{{ route('admin.authors.index') }}"
                class="hover:text-primary"
            >
                Authors
            </a>

            <i class="fas fa-chevron-right text-[10px]"></i>

            <a
                href="{{ route('admin.authors.show', $author) }}"
                class="hover:text-primary"
            >
                {{ $author->name }}
            </a>

            <i class="fas fa-chevron-right text-[10px]"></i>

            <span>Edit</span>
        </div>

        <h1 class="mt-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
            Edit Author
        </h1>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Update the author's information.
        </p>
    </div>

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 dark:border-red-900/50 dark:bg-red-900/20">

            <div class="flex items-start gap-3">

                <i class="fas fa-exclamation-circle mt-0.5 text-red-500"></i>

                <div>
                    <p class="text-sm font-medium text-red-700 dark:text-red-400">
                        Please correct the following errors:
                    </p>

                    <ul class="mt-2 list-inside list-disc space-y-1 text-xs text-red-600 dark:text-red-400">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>

        </div>
    @endif

    {{-- Form --}}
    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

        <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-800">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <h2 class="text-base font-semibold text-gray-800 dark:text-white/90">
                        Author Information
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Update the author's basic information.
                    </p>
                </div>

                <span class="hidden rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600 sm:inline-flex dark:bg-gray-800 dark:text-gray-400">
                    ID #{{ $author->id }}
                </span>

            </div>

        </div>

        <form
            method="POST"
            action="{{ route('admin.authors.update', $author) }}"
            class="p-6"
        >
            @csrf
            @method('PUT')

            <div class="space-y-6">

                {{-- Author Name --}}
                <div>

                    <label
                        for="name"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Author Name
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $author->name) }}"
                        maxlength="255"
                        required
                        autofocus
                        placeholder="e.g. William Shakespeare"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500"
                    >

                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Biography --}}
                <div>

                    <label
                        for="bio"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Biography
                    </label>

                    <textarea
                        id="bio"
                        name="bio"
                        rows="8"
                        maxlength="5000"
                        placeholder="Write a short biography about the author..."
                        class="w-full resize-y rounded-lg border border-gray-300 bg-transparent px-3 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500"
                    >{{ old('bio', $author->bio) }}</textarea>

                    @error('bio')
                        <p class="mt-1.5 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                    <div class="mt-1.5 flex justify-between gap-4">

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Optional. Maximum 5,000 characters.
                        </p>

                        <span
                            id="bio-counter"
                            class="text-xs text-gray-400"
                        >
                            0 / 5000
                        </span>

                    </div>

                </div>

            </div>

            {{-- Actions --}}
            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">

                {{-- Delete --}}
                <div>

                    @can('authors.delete', 'admin')

                        @if($author->books()->count() === 0)

                            <button
                                type="button"
                                id="delete-author-button"
                                class="inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                            >
                                <i class="fas fa-trash-alt"></i>
                                Delete Author
                            </button>

                        @else

                            <span
                                class="inline-flex cursor-not-allowed items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-gray-400"
                                title="This author has books assigned."
                            >
                                <i class="fas fa-lock"></i>
                                Author In Use
                            </span>

                        @endif

                    @endcan

                </div>

                {{-- Right actions --}}
                <div class="flex flex-col-reverse gap-3 sm:flex-row">

                    <a
                        href="{{ route('admin.authors.show', $author) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white transition hover:bg-primary/90"
                    >
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

{{-- Delete form --}}
<form
    id="delete-author-form"
    method="POST"
    action="{{ route('admin.authors.destroy', $author) }}"
    class="hidden"
>
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', () => {

    /*
     * Biography character counter
     */
    const bio = document.getElementById('bio');
    const bioCounter = document.getElementById('bio-counter');

    const updateBioCounter = () => {
        if (!bio || !bioCounter) {
            return;
        }

        bioCounter.textContent = `${bio.value.length} / 5000`;
    };

    if (bio) {
        bio.addEventListener('input', updateBioCounter);
        updateBioCounter();
    }


    /*
     * Delete author confirmation
     */
    const deleteButton = document.getElementById('delete-author-button');
    const deleteForm = document.getElementById('delete-author-form');

    if (deleteButton && deleteForm) {

        deleteButton.addEventListener('click', async () => {

            const result = await Swal.fire({
                title: 'Delete author?',
                text: `Are you sure you want to delete "{{ $author->name }}"?`,
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

            deleteForm.submit();
        });

    }

});
</script>

@endpush