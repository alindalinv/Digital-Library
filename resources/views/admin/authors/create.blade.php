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

            <span>Create</span>
        </div>

        <h1 class="mt-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
            Create Author
        </h1>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Add a new author to your digital library.
        </p>
    </div>

    {{-- Form --}}
    <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

        <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-800">
            <h2 class="text-base font-semibold text-gray-800 dark:text-white/90">
                Author Information
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Enter the author's basic information.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('admin.authors.store') }}"
            class="p-6"
        >
            @csrf

            <div class="space-y-6">

                {{-- Name --}}
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
                        value="{{ old('name') }}"
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
                        rows="7"
                        maxlength="5000"
                        placeholder="Write a short biography about the author..."
                        class="w-full resize-y rounded-lg border border-gray-300 bg-transparent px-3 py-2.5 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500"
                    >{{ old('bio') }}</textarea>

                    @error('bio')
                        <p class="mt-1.5 text-xs text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                        Optional. Maximum 5,000 characters.
                    </p>
                </div>

            </div>

            {{-- Actions --}}
            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end dark:border-gray-800">

                <a
                    href="{{ route('admin.authors.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white transition hover:bg-primary/90"
                >
                    <i class="fas fa-save"></i>
                    Create Author
                </button>

            </div>

        </form>

    </div>

</div>

@endsection