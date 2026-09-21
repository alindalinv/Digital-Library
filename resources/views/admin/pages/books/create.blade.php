@extends('layouts.admin.app')

@section('content')
    <x-admin.common.page-breadcrumb pageTitle="Create Book" />

    @php
        // Reusable Tailwind class strings — single source of truth.
        $baseInput   = 'w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500';
        $selectInput = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-blue-500';
        $labelClass  = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300';
        $errorClass  = 'mt-1.5 text-xs text-red-500 dark:text-red-400';
        $hintClass   = 'mt-1.5 text-xs text-gray-500 dark:text-gray-400';

        $selectedAuthors = old('authors', []);
    @endphp

    <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-gray-900">

        {{-- =========================================================
             Header
        ========================================================== --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Create Book
                </h3>

                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                    Add a new book to your library catalog.
                </p>
            </div>

            <a href="{{ route('admin.books.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">

                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>

                Back to Books
            </a>
        </div>


        {{-- =========================================================
             Validation Summary
        ========================================================== --}}
        @if ($errors->any())
            <div role="alert" aria-live="polite"
                 class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-900/50 dark:bg-red-900/10">
                <div class="flex gap-3">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
             Create Book Form
        ========================================================== --}}
        <form method="POST"
              action="{{ route('admin.books.store') }}"
              enctype="multipart/form-data"
              id="createBookForm"
              novalidate>

            @csrf

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- =================================================
                     LEFT COLUMN
                ================================================== --}}
                <div class="space-y-5 lg:col-span-2">

                    {{-- Title --}}
                    <div>
                        <label for="title" class="{{ $labelClass }}">
                            Title <span class="text-red-500" aria-hidden="true">*</span>
                        </label>

                        <input type="text" id="title" name="title"
                               value="{{ old('title') }}"
                               required autofocus
                               aria-required="true"
                               @error('title') aria-invalid="true" aria-describedby="title-error" @enderror
                               class="{{ $baseInput }}">

                        @error('title')
                            <p id="title-error" class="{{ $errorClass }}">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Slug --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="slug" class="{{ $labelClass }}">Slug</label>

                            <button type="button"
                                    id="resetSlug"
                                    class="mb-1.5 text-xs font-medium text-blue-600 hover:underline dark:text-blue-400">
                                Reset to auto
                            </button>
                        </div>

                        <input type="text" id="slug" name="slug"
                               value="{{ old('slug') }}"
                               placeholder="auto-generated from title if empty"
                               aria-describedby="slug-help @error('slug') slug-error @enderror"
                               @error('slug') aria-invalid="true" @enderror
                               class="{{ $baseInput }}">

                        <p id="slug-help" class="{{ $hintClass }}">
                            Use lowercase letters, numbers, and hyphens.
                        </p>

                        @error('slug')
                            <p id="slug-error" class="{{ $errorClass }}">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- ISBN --}}
                    <div>
                        <label for="isbn" class="{{ $labelClass }}">ISBN</label>

                        <input type="text" id="isbn" name="isbn"
                               value="{{ old('isbn') }}"
                               placeholder="978-3-16-148410-0"
                               @error('isbn') aria-invalid="true" aria-describedby="isbn-error" @enderror
                               class="{{ $baseInput }}">

                        @error('isbn')
                            <p id="isbn-error" class="{{ $errorClass }}">{{ $message }}</p>
                        @enderror
                    </div>


                   {{-- Description (Rich Text) --}}
                    <div>
                        <label for="description" class="{{ $labelClass }}">Description</label>

                        {{-- Fallback textarea — hidden by JS once TinyMCE initializes --}}
                        <textarea id="description" name="description"
                                @error('description') aria-invalid="true" @enderror
                                aria-describedby="description-help @error('description') description-error @enderror"
                                class="w-full resize-y rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:text-white/90 dark:placeholder:text-gray-500 dark:focus:border-blue-500">{{ old('description', $book->description ?? '') }}</textarea>

                        <p id="description-help" class="{{ $hintClass }}">
                            Format with bold, italic, lists, and links. Paste from Word is supported.
                        </p>

                        @error('description')
                            <p id="description-error" class="{{ $errorClass }}">{{ $message }}</p>
                        @enderror

                        <noscript>
                            <p class="mt-2 rounded-lg border border-yellow-200 bg-yellow-50 p-3 text-xs text-yellow-700 dark:border-yellow-900/50 dark:bg-yellow-900/10 dark:text-yellow-400">
                                Rich text formatting requires JavaScript. Please enable JavaScript to use the editor.
                            </p>
                        </noscript>
                    </div>
                    {{-- Authors --}}
                    <div>
                        <label for="authors" class="{{ $labelClass }}">Authors</label>

                        <select id="authors" name="authors[]" multiple size="6"
                                aria-describedby="authors-help"
                                @error('authors') aria-invalid="true" aria-describedby="authors-error" @enderror
                                class="{{ $selectInput }}">

                            @foreach($authors as $author)
                                <option value="{{ $author->id }}"
                                        @selected(in_array((string) $author->id, array_map('strval', $selectedAuthors), true))
                                        class="dark:bg-gray-900">
                                    {{ $author->name }}
                                </option>
                            @endforeach

                        </select>

                        <p id="authors-help" class="{{ $hintClass }}">
                            Hold
                            <kbd data-mod-key class="rounded bg-gray-100 px-1.5 py-0.5 text-xs dark:bg-gray-800">Ctrl</kbd>
                            to select multiple authors.
                        </p>

                        @error('authors')
                            <p id="authors-error" class="{{ $errorClass }}">{{ $message }}</p>
                        @enderror

                        @error('authors.*')
                            <p class="{{ $errorClass }}">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Category / Publisher --}}
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        {{-- Category --}}
                        <div>
                            <label for="category_id" class="{{ $labelClass }}">
                                Category <span class="text-red-500" aria-hidden="true">*</span>
                            </label>

                            <select id="category_id" name="category_id" required aria-required="true"
                                    @error('category_id') aria-invalid="true" aria-describedby="category-error" @enderror
                                    class="{{ $selectInput }}">

                                <option value="" class="dark:bg-gray-900">— Select Category —</option>

                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            @selected(old('category_id') == $category->id)
                                            class="dark:bg-gray-900">
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('category_id')
                                <p id="category-error" class="{{ $errorClass }}">{{ $message }}</p>
                            @enderror
                        </div>


                        {{-- Publisher --}}
                        <div>
                            <label for="publisher_id" class="{{ $labelClass }}">Publisher</label>

                            <select id="publisher_id" name="publisher_id"
                                    @error('publisher_id') aria-invalid="true" aria-describedby="publisher-error" @enderror
                                    class="{{ $selectInput }}">

                                <option value="" class="dark:bg-gray-900">— Select Publisher —</option>

                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher->id }}"
                                            @selected(old('publisher_id') == $publisher->id)
                                            class="dark:bg-gray-900">
                                        {{ $publisher->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('publisher_id')
                                <p id="publisher-error" class="{{ $errorClass }}">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>


                    {{-- Published Year / Language / Pages --}}
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                        <div>
                            <label for="published_year" class="{{ $labelClass }}">Published Year</label>

                            <input type="number" id="published_year" name="published_year"
                                   value="{{ old('published_year') }}"
                                   min="1000" max="{{ date('Y') + 1 }}"
                                   placeholder="{{ date('Y') }}"
                                   class="{{ $baseInput }}">

                            @error('published_year')
                                <p class="{{ $errorClass }}">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="language" class="{{ $labelClass }}">Language</label>

                            <input type="text" id="language" name="language"
                                   value="{{ old('language', 'English') }}"
                                   placeholder="English"
                                   class="{{ $baseInput }}">

                            @error('language')
                                <p class="{{ $errorClass }}">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="pages" class="{{ $labelClass }}">Pages</label>

                            <input type="number" id="pages" name="pages"
                                   value="{{ old('pages') }}"
                                   min="1" max="99999" placeholder="320"
                                   class="{{ $baseInput }}">

                            @error('pages')
                                <p class="{{ $errorClass }}">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>


                    {{-- Price / Stock --}}
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        <div>
                            <label for="price" class="{{ $labelClass }}">Price ($)</label>

                            <input type="number" id="price" name="price"
                                   value="{{ old('price') }}"
                                   min="0" max="999999.99" step="0.01" placeholder="0.00"
                                   class="{{ $baseInput }}">

                            @error('price')
                                <p class="{{ $errorClass }}">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="stock" class="{{ $labelClass }}">
                                Stock <span class="text-red-500" aria-hidden="true">*</span>
                            </label>

                            <input type="number" id="stock" name="stock"
                                   value="{{ old('stock', 0) }}"
                                   min="0" required aria-required="true"
                                   placeholder="10"
                                   class="{{ $baseInput }}">

                            @error('stock')
                                <p class="{{ $errorClass }}">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                </div>


                {{-- =================================================
                     RIGHT COLUMN
                ================================================== --}}
                <div class="space-y-5">

                    {{-- Cover Image --}}
                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">

                        <label for="cover_image" class="{{ $labelClass }}">Cover Image</label>

                        <div id="coverPreviewContainer" class="mb-4 flex justify-center">
                            <div id="coverPlaceholder"
                                 class="flex h-64 w-44 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 text-center dark:border-gray-700 dark:bg-gray-800">

                                <div class="px-4">
                                    <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>

                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        No cover image
                                    </p>
                                </div>
                            </div>
                        </div>

                        <input type="file" id="cover_image" name="cover_image"
                               accept="image/jpeg,image/png,image/webp"
                               aria-describedby="cover-help"
                               class="block w-full text-sm text-gray-500 dark:text-gray-400
                                      file:mr-3 file:rounded-full file:border-0
                                      file:bg-blue-50 file:px-4 file:py-2
                                      file:text-sm file:font-medium file:text-blue-600
                                      hover:file:bg-blue-100
                                      dark:file:bg-blue-500/15 dark:file:text-blue-400
                                      dark:hover:file:bg-blue-500/25">

                        <p id="cover-help" class="{{ $hintClass }}">
                            JPG, PNG or WEBP. Maximum size: 2MB.
                        </p>

                        {{-- Inline client-side error --}}
                        <p id="coverError" class="hidden {{ $errorClass }}"></p>

                        @error('cover_image')
                            <p class="{{ $errorClass }}">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Status --}}
                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">

                        <label for="status" class="{{ $labelClass }}">
                            Status <span class="text-red-500" aria-hidden="true">*</span>
                        </label>

                       <select id="status" name="status" required aria-required="true"
                                @error('status') aria-invalid="true" aria-describedby="status-error" @enderror
                                class="{{ $selectInput }}">

                            @foreach (['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'] as $value => $label)
                                <option value="{{ $value }}"
                                        @selected(old('status', 'published') === $value)
                                        class="dark:bg-gray-900">
                                    {{ $label }}
                                </option>
                            @endforeach

                        </select>

                        @error('status')
                            <p id="status-error" class="{{ $errorClass }}">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Featured --}}
                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                        <label class="flex cursor-pointer items-start gap-3">
                            {{-- Hidden fallback ensures "0" is always submitted --}}
                            <input type="hidden" name="is_featured" value="0">

                            <input type="checkbox"
                                   id="is_featured"
                                   name="is_featured"
                                   value="1"
                                   @checked(old('is_featured'))
                                   class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500/30 dark:border-gray-600 dark:bg-gray-800">

                            <div>
                                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Featured Book
                                </span>
                                <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">
                                    Show this book in featured sections on the storefront.
                                </span>
                            </div>
                        </label>

                        @error('is_featured')
                            <p class="{{ $errorClass }}">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- Tips --}}
                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-900/50 dark:bg-blue-900/10">
                        <div class="flex gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>

                            <div>
                                <h4 class="text-sm font-semibold text-blue-700 dark:text-blue-400">
                                    Tips
                                </h4>

                                <ul class="mt-2 list-inside list-disc space-y-1 text-xs text-blue-600 dark:text-blue-400">
                                    <li>Slug is auto-generated from the title if left empty.</li>
                                    <li>Set status to <strong>Draft</strong> until ready to publish.</li>
                                    <li>You can add multiple authors by holding
                                        <span data-mod-key>Ctrl</span>.
                                    </li>
                                </ul>
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
                   data-cancel
                   class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Cancel
                </a>

                <button type="submit" id="createBookButton"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/30 disabled:cursor-not-allowed disabled:opacity-60">

                    <svg id="createBookSpinner" class="hidden h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>

                    <svg id="createBookIcon" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>

                    <span id="createBookText">Create Book</span>

                </button>

            </div>

        </form>

    </div>


    {{-- =========================================================
         JavaScript
    ========================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form         = document.getElementById('createBookForm');
            const coverInput   = document.getElementById('cover_image');
            const coverError   = document.getElementById('coverError');
            const createBtn    = document.getElementById('createBookButton');
            const createText   = document.getElementById('createBookText');
            const spinner      = document.getElementById('createBookSpinner');
            const icon         = document.getElementById('createBookIcon');
            const titleInput   = document.getElementById('title');
            const slugInput    = document.getElementById('slug');
            const resetSlugBtn = document.getElementById('resetSlug');

            let currentObjectUrl = null;
            let isDirty = false;

            /* -----------------------------------------------------
             * 0. Platform-aware modifier key (Cmd on Mac, Ctrl else)
             * --------------------------------------------------- */
            const isMac = (navigator.platform || navigator.userAgent || '')
                .toUpperCase().includes('MAC');

            if (isMac) {
                document.querySelectorAll('[data-mod-key]').forEach((el) => {
                    el.textContent = 'Cmd';
                });
            }

            /* -----------------------------------------------------
             * 1. Dirty-state tracking (unsaved changes warning)
             * --------------------------------------------------- */
            form?.addEventListener('input', (e) => {
                // Ignore programmatic changes (e.g., slug autofill)
                if (!e.isTrusted) return;
                isDirty = true;
            }, { passive: true });

            form?.addEventListener('change', () => { isDirty = true; }, { passive: true });

            window.addEventListener('beforeunload', (e) => {
                if (!isDirty) return;
                e.preventDefault();
                e.returnValue = '';
            });

            /* -----------------------------------------------------
             * 2. Cancel link: confirm before leaving with dirty state
             * --------------------------------------------------- */
            document.querySelector('[data-cancel]')?.addEventListener('click', (e) => {
                if (isDirty && !confirm('You have unsaved changes. Discard them?')) {
                    e.preventDefault();
                    return;
                }
                isDirty = false;
            });

            /* -----------------------------------------------------
             * 3. Slug auto-generation from title
             * --------------------------------------------------- */
            const slugify = (str) =>
                str.toLowerCase().trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_]+/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-+|-+$/g, '');

            let slugManuallyEdited = false;

            slugInput?.addEventListener('input', () => {
                slugManuallyEdited = true;
                delete slugInput.dataset.autofilled;
            });

            titleInput?.addEventListener('input', () => {
                if (slugManuallyEdited || !slugInput) return;
                slugInput.value = slugify(titleInput.value);
                slugInput.dataset.autofilled = 'true';
            });

            /* -----------------------------------------------------
             * 3b. "Reset to auto" — force slug from current title
             * --------------------------------------------------- */
            resetSlugBtn?.addEventListener('click', () => {
                if (!slugInput || !titleInput) return;
                slugManuallyEdited = false;
                slugInput.value = slugify(titleInput.value);
                slugInput.dataset.autofilled = 'true';
                isDirty = true;
            });

            /* -----------------------------------------------------
             * 4. Cover image preview with memory cleanup
             * --------------------------------------------------- */
            const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
            const MAX_SIZE = 2 * 1024 * 1024;

            const showCoverError = (message) => {
                if (!coverError) return;
                coverError.textContent = message;
                coverError.classList.remove('hidden');
            };

            const clearCoverError = () => {
                if (!coverError) return;
                coverError.textContent = '';
                coverError.classList.add('hidden');
            };

            coverInput?.addEventListener('change', (event) => {
                clearCoverError();

                const file = event.target.files?.[0];
                if (!file) return;

                if (!ALLOWED_TYPES.includes(file.type)) {
                    showCoverError('Please select a JPG, PNG, or WEBP image.');
                    coverInput.value = '';
                    return;
                }

                if (file.size > MAX_SIZE) {
                    showCoverError('The cover image must not be larger than 2MB.');
                    coverInput.value = '';
                    return;
                }

                if (currentObjectUrl) {
                    URL.revokeObjectURL(currentObjectUrl);
                }
                currentObjectUrl = URL.createObjectURL(file);

                let preview = document.getElementById('coverPreview');
                const placeholder = document.getElementById('coverPlaceholder');

                if (!preview) {
                    placeholder?.remove();
                    preview = document.createElement('img');
                    preview.id = 'coverPreview';
                    preview.alt = 'Cover preview';
                    preview.className = 'h-64 w-44 rounded-lg border border-gray-200 object-cover shadow-sm dark:border-gray-700';
                    document.getElementById('coverPreviewContainer')?.appendChild(preview);
                }

                preview.src = currentObjectUrl;
                isDirty = true;
            });

            /* -----------------------------------------------------
             * 5. Prevent double submission (guards Enter key too)
             * --------------------------------------------------- */
            form?.addEventListener('submit', (e) => {
                if (form.dataset.submitting === 'true') {
                    e.preventDefault();
                    return;
                }

                form.dataset.submitting = 'true';

                createBtn && (createBtn.disabled = true);
                spinner?.classList.remove('hidden');
                icon?.classList.add('hidden');
                if (createText) createText.textContent = 'Creating...';

                isDirty = false;
            });
        });
    </script>
    @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const editorEl = document.getElementById('description');
    if (!editorEl || typeof tinymce === 'undefined') return;

    const isDark = document.documentElement.classList.contains('dark');

    tinymce.init({
        target: editorEl,

        /* -----------------------------------------------------
         * Core setup
         * --------------------------------------------------- */
        menubar: false,
        height: 400,
        branding: false,
        promotion: false,
        license_key: 'gpl', // 👈 required for TinyMCE 7 (GPL license)

        /* -----------------------------------------------------
         * Plugins
         * --------------------------------------------------- */
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
            'preview', 'anchor', 'searchreplace', 'visualblocks', 'code',
            'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],

        /* -----------------------------------------------------
         * Toolbar
         * --------------------------------------------------- */
        toolbar:
            'undo redo | ' +
            'blocks | ' +
            'bold italic underline strikethrough | ' +
            'forecolor backcolor | ' +
            'alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | ' +
            'link image media table | ' +
            'removeformat code fullscreen | ' +
            'help',

        /* -----------------------------------------------------
         * Content styling (matches Tailwind design system)
         * --------------------------------------------------- */
        content_style: `
            body {
                font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
                font-size: 14px;
                line-height: 1.6;
                color: ${isDark ? '#e5e7eb' : '#1f2937'};
                background: ${isDark ? '#111827' : '#ffffff'};
                padding: 12px 16px;
                margin: 0;
            }
            p { margin: 0 0 0.75em; }
            a { color: #2563eb; text-decoration: underline; }
            h1 { font-size: 1.5em; font-weight: 700; margin: 0.75em 0 0.5em; }
            h2 { font-size: 1.25em; font-weight: 600; margin: 0.75em 0 0.5em; }
            h3 { font-size: 1.1em; font-weight: 600; margin: 0.75em 0 0.5em; }
            ul, ol { margin: 0 0 0.75em 1.25em; padding: 0; }
            li { margin: 0.25em 0; }
            blockquote {
                border-left: 3px solid ${isDark ? '#374151' : '#d1d5db'};
                padding-left: 12px;
                color: ${isDark ? '#9ca3af' : '#6b7280'};
                margin: 0.75em 0;
            }
            img { max-width: 100%; height: auto; }
        `,

        /* -----------------------------------------------------
         * UI skin (dark mode aware)
         * --------------------------------------------------- */
        skin: isDark ? 'oxide-dark' : 'oxide',
        content_css: isDark ? 'dark' : 'default',

        /* -----------------------------------------------------
         * Image upload (optional — see next section)
         * --------------------------------------------------- */
        automatic_uploads: true,
        images_upload_url: '{{ route("admin.books.upload-image") }}',
        images_upload_credentials: true,
        images_upload_handler: (blobInfo) => new Promise((resolve, reject) => {
            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            fetch('{{ route("admin.books.upload-image") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            })
            .then((response) => {
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                return response.json();
            })
            .then((json) => {
                if (!json.location) {
                    reject('Upload failed: no location returned.');
                    return;
                }
                resolve(json.location);
            })
            .catch((err) => {
                console.error('Image upload failed:', err);
                reject('Image upload failed. Please try again.');
            });
        }),

        /* -----------------------------------------------------
         * URL handling
         * --------------------------------------------------- */
        convert_urls: false,
        relative_urls: false,

        /* -----------------------------------------------------
         * Paste behavior
         * --------------------------------------------------- */
        paste_as_text: false,
        paste_data_images: true, // 👈 allows pasting screenshots

        /* -----------------------------------------------------
         * Mobile
         * --------------------------------------------------- */
        mobile: {
            menubar: false,
            toolbar: 'undo redo | bold italic | bullist numlist | link | removeformat',
        },

        /* -----------------------------------------------------
         * Setup — hook into events
         * --------------------------------------------------- */
        setup: (editor) => {
            editor.on('init', () => {
                // Ensure the underlying textarea stays in sync for native validation
                editor.save();
            });

            // Mark form dirty when content changes (integrates with your beforeunload)
            editor.on('change keyup undo redo', () => {
                editor.save();

                // If your parent uses a `form` with dirty tracking:
                const form = document.getElementById('createBookForm')
                          || document.getElementById('editBookForm');
                if (form) {
                    form.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        },
    });
});
</script>
@endpush
@endsection