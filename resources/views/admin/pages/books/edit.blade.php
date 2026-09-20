@extends('layouts.admin.app')

@section('content')
<x-admin.common.page-breadcrumb pageTitle="Edit Book" />

<div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-gray-900">

    {{-- =========================================================
         Header
    ========================================================== --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Edit Book
            </h3>

            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                Update the details for "{{ $book->title }}".
            </p>
        </div>

        <a href="{{ route('admin.books.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">

            <svg class="h-4 w-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>

            Back to Books
        </a>
    </div>


    {{-- =========================================================
         Validation Summary
    ========================================================== --}}
    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-900/50 dark:bg-red-900/10">
            <div class="flex gap-3">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 8v4m0 4h.01M10.29 3.86l-7.82 13.5A2 2 0 004.2 20.36h15.6a2 2 0 001.73-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>

                <div>
                    <h4 class="text-sm font-semibold text-red-700 dark:text-red-400">
                        Please correct the following errors:
                    </h4>

                    <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-red-600 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif


    {{-- =========================================================
         Edit Book Form
    ========================================================== --}}
    <form method="POST"
          action="{{ route('admin.books.update', $book) }}"
          enctype="multipart/form-data"
          id="editBookForm">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">


            {{-- =================================================
                 LEFT COLUMN
            ================================================== --}}
            <div class="space-y-5 lg:col-span-2">


                {{-- =================================================
                     Title
                ================================================== --}}
                <div>
                    <label for="title"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Title <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', $book->title) }}"
                           required
                           autofocus
                           class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">

                    @error('title')
                        <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- =================================================
                     Slug
                ================================================== --}}
                <div>
                    <label for="slug"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Slug
                    </label>

                    <input type="text"
                           id="slug"
                           name="slug"
                           value="{{ old('slug', $book->slug) }}"
                           placeholder="auto-generated from title if empty"
                           class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                        Use lowercase letters, numbers, and hyphens.
                    </p>

                    @error('slug')
                        <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- =================================================
                     ISBN
                ================================================== --}}
                <div>
                    <label for="isbn"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        ISBN
                    </label>

                    <input type="text"
                           id="isbn"
                           name="isbn"
                           value="{{ old('isbn', $book->isbn) }}"
                           placeholder="978-3-16-148410-0"
                           class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">

                    @error('isbn')
                        <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- =================================================
                     Description
                ================================================== --}}
                <div>
                    <label for="description"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Description
                    </label>

                    <textarea id="description"
                              name="description"
                              rows="6"
                              placeholder="Book summary, synopsis, or notes..."
                              class="w-full resize-y rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">{{ old('description', $book->description) }}</textarea>

                    @error('description')
                        <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- =================================================
                     Authors
                ================================================== --}}
                <div>
                    <label for="authors"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Authors
                    </label>

                    @php
                        $selectedAuthors = old(
                            'authors',
                            $book->authors->pluck('id')->toArray()
                        );
                    @endphp

                    <select id="authors"
                            name="authors[]"
                            multiple
                            size="6"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-blue-500">

                        @foreach($authors as $author)
                            <option value="{{ $author->id }}"
                                @selected(in_array($author->id, $selectedAuthors))
                                class="dark:bg-gray-900">
                                {{ $author->name }}
                            </option>
                        @endforeach

                    </select>

                    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                        Hold
                        <kbd class="rounded bg-gray-100 px-1.5 py-0.5 text-xs dark:bg-gray-800">
                            Ctrl
                        </kbd>
                        or
                        <kbd class="rounded bg-gray-100 px-1.5 py-0.5 text-xs dark:bg-gray-800">
                            Cmd
                        </kbd>
                        to select multiple authors.
                    </p>

                    @error('authors')
                        <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                            {{ $message }}
                        </p>
                    @enderror

                    @error('authors.*')
                        <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- =================================================
                     Category / Publisher
                ================================================== --}}
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Category --}}
                    <div>
                        <label for="category_id"
                               class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Category <span class="text-red-500">*</span>
                        </label>

                        <select id="category_id"
                                name="category_id"
                                required
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-blue-500">

                            <option value="" class="dark:bg-gray-900">
                                — Select Category —
                            </option>

                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected(old('category_id', $book->category_id) == $category->id)
                                    class="dark:bg-gray-900">
                                    {{ $category->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('category_id')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Publisher --}}
                    <div>
                        <label for="publisher_id"
                               class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Publisher
                        </label>

                        <select id="publisher_id"
                                name="publisher_id"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-blue-500">

                            <option value="" class="dark:bg-gray-900">
                                — Select Publisher —
                            </option>

                            @foreach($publishers as $publisher)
                                <option value="{{ $publisher->id }}"
                                    @selected(old('publisher_id', $book->publisher_id) == $publisher->id)
                                    class="dark:bg-gray-900">
                                    {{ $publisher->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('publisher_id')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>


                {{-- =================================================
                     Published Year / Language / Pages
                ================================================== --}}
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                    {{-- Published Year --}}
                    <div>
                        <label for="published_year"
                               class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Published Year
                        </label>

                        <input type="number"
                               id="published_year"
                               name="published_year"
                               value="{{ old('published_year', $book->published_year) }}"
                               min="1000"
                               max="{{ date('Y') + 1 }}"
                               placeholder="{{ date('Y') }}"
                               class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">

                        @error('published_year')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Language --}}
                    <div>
                        <label for="language"
                               class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Language
                        </label>

                        <input type="text"
                               id="language"
                               name="language"
                               value="{{ old('language', $book->language) }}"
                               placeholder="English"
                               class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">

                        @error('language')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Pages --}}
                    <div>
                        <label for="pages"
                               class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Pages
                        </label>

                        <input type="number"
                               id="pages"
                               name="pages"
                               value="{{ old('pages', $book->pages) }}"
                               min="1"
                               placeholder="320"
                               class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">

                        @error('pages')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>


                {{-- =================================================
                     Price / Stock
                ================================================== --}}
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Price --}}
                    <div>
                        <label for="price"
                               class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Price ($)
                        </label>

                        <input type="number"
                               id="price"
                               name="price"
                               value="{{ old('price', $book->price) }}"
                               min="0"
                               step="0.01"
                               placeholder="0.00"
                               class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">

                        @error('price')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Stock --}}
                    <div>
                        <label for="stock"
                               class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Stock <span class="text-red-500">*</span>
                        </label>

                        <input type="number"
                               id="stock"
                               name="stock"
                               value="{{ old('stock', $book->stock) }}"
                               min="0"
                               required
                               placeholder="10"
                               class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">

                        @error('stock')
                            <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

            </div>


            {{-- =================================================
                 RIGHT COLUMN
            ================================================== --}}
            <div class="space-y-5">


                {{-- =================================================
                     Cover Image
                ================================================== --}}
                <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">

                    <label for="cover_image"
                           class="mb-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Cover Image
                    </label>


                    {{-- Image Preview --}}
                    <div class="mb-4 flex justify-center">
                        @if ($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}"
                                 alt="{{ $book->title }}"
                                 id="coverPreview"
                                 class="h-64 w-44 rounded-lg border border-gray-200 object-cover shadow-sm dark:border-gray-700">
                        @else
                            <div id="coverPlaceholder"
                                 class="flex h-64 w-44 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 text-center dark:border-gray-700 dark:bg-gray-800">

                                <div class="px-4">
                                    <svg class="mx-auto h-10 w-10 text-gray-400"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="1.5"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>

                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        No cover image
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>


                    {{-- File Input --}}
                    <input type="file"
                           id="cover_image"
                           name="cover_image"
                           accept="image/jpeg,image/png,image/webp"
                           class="block w-full text-sm text-gray-500 dark:text-gray-400
                                  file:mr-3 file:rounded-full file:border-0
                                  file:bg-blue-50 file:px-4 file:py-2
                                  file:text-sm file:font-medium file:text-blue-600
                                  hover:file:bg-blue-100
                                  dark:file:bg-blue-500/15 dark:file:text-blue-400
                                  dark:hover:file:bg-blue-500/25">

                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        JPG, PNG or WEBP. Maximum size: 2MB.
                    </p>

                    @error('cover_image')
                        <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- =================================================
                     Status
                ================================================== --}}
                <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">

                    <label for="status"
                           class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Status <span class="text-red-500">*</span>
                    </label>

                    <select id="status"
                            name="status"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-blue-500">

                        <option value="draft"
                            @selected(old('status', $book->status) === 'draft')
                            class="dark:bg-gray-900">
                            Draft
                        </option>

                        <option value="published"
                            @selected(old('status', $book->status) === 'published')
                            class="dark:bg-gray-900">
                            Published
                        </option>

                        <option value="archived"
                            @selected(old('status', $book->status) === 'archived')
                            class="dark:bg-gray-900">
                            Archived
                        </option>

                    </select>

                    @error('status')
                        <p class="mt-1.5 text-xs text-red-500 dark:text-red-400">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- =================================================
                     Book Information
                ================================================== --}}
                <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">

                    <h4 class="mb-4 text-sm font-semibold text-gray-800 dark:text-white/90">
                        Book Information
                    </h4>

                    <div class="space-y-3 text-sm">

                        <div class="flex items-center justify-between gap-3">
                            <span class="text-gray-500 dark:text-gray-400">
                                Book ID
                            </span>

                            <span class="font-medium text-gray-800 dark:text-gray-200">
                                #{{ $book->id }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-3">
                            <span class="text-gray-500 dark:text-gray-400">
                                Created
                            </span>

                            <span class="text-right text-gray-800 dark:text-gray-200">
                                {{ $book->created_at?->format('M d, Y H:i') ?? '—' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-3">
                            <span class="text-gray-500 dark:text-gray-400">
                                Last Updated
                            </span>

                            <span class="text-right text-gray-800 dark:text-gray-200">
                                {{ $book->updated_at?->format('M d, Y H:i') ?? '—' }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             Form Actions
        ========================================================== --}}
        <div class="mt-6 flex flex-col-reverse gap-3 border-t border-gray-200 pt-5 sm:flex-row sm:items-center sm:justify-end dark:border-gray-800">

            <a href="{{ route('admin.books.index') }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                Cancel
            </a>

            <button type="submit"
                    id="updateBookButton"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30 disabled:cursor-not-allowed disabled:opacity-60">

                <svg id="updateBookSpinner"
                     class="hidden h-4 w-4 animate-spin"
                     xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24">

                    <circle class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4">
                    </circle>

                    <path class="opacity-75"
                          fill="currentColor"
                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                    </path>
                </svg>

                <svg id="updateBookIcon"
                     class="h-4 w-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>

                <span id="updateBookText">
                    Update Book
                </span>

            </button>

        </div>

    </form>

</div>


{{-- =========================================================
     JavaScript
========================================================== --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('editBookForm');
        const coverInput = document.getElementById('cover_image');
        const updateButton = document.getElementById('updateBookButton');
        const updateText = document.getElementById('updateBookText');
        const spinner = document.getElementById('updateBookSpinner');
        const icon = document.getElementById('updateBookIcon');

        /*
         * ---------------------------------------------------------
         * Cover image preview
         * ---------------------------------------------------------
         */
        if (coverInput) {
            coverInput.addEventListener('change', function (event) {

                const file = event.target.files[0];

                if (!file) {
                    return;
                }

                /*
                 * Check image type.
                 */
                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];

                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a JPG, PNG, or WEBP image.');
                    coverInput.value = '';
                    return;
                }

                /*
                 * Check maximum size: 2MB.
                 */
                const maxSize = 2 * 1024 * 1024;

                if (file.size > maxSize) {
                    alert('The cover image must not be larger than 2MB.');
                    coverInput.value = '';
                    return;
                }

                /*
                 * Create preview.
                 */
                const objectUrl = URL.createObjectURL(file);

                let preview = document.getElementById('coverPreview');

                if (!preview) {

                    const placeholder = document.getElementById('coverPlaceholder');

                    if (placeholder) {
                        placeholder.remove();
                    }

                    preview = document.createElement('img');

                    preview.id = 'coverPreview';
                    preview.alt = 'Cover preview';
                    preview.className =
                        'h-64 w-44 rounded-lg border border-gray-200 object-cover shadow-sm dark:border-gray-700';

                    const previewContainer = coverInput
                        .closest('.rounded-xl')
                        .querySelector('.mb-4');

                    if (previewContainer) {
                        previewContainer.appendChild(preview);
                    }
                }

                preview.src = objectUrl;
            });
        }


        /*
         * ---------------------------------------------------------
         * Prevent double submission
         * ---------------------------------------------------------
         */
        if (form) {
            form.addEventListener('submit', function () {

                if (updateButton) {
                    updateButton.disabled = true;
                }

                if (spinner) {
                    spinner.classList.remove('hidden');
                }

                if (icon) {
                    icon.classList.add('hidden');
                }

                if (updateText) {
                    updateText.textContent = 'Updating...';
                }
            });
        }

    });
</script>

@endsection
