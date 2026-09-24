<div>
    <label for="book_id" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
        Book
    </label>

    @php
        $isEdit        = isset($ebookFile) && $ebookFile !== null;
        $currentBookId = old('book_id', $ebookFile?->book_id ?? $selectedBookId);
    @endphp

    @if ($isEdit)
        {{-- Hidden input so the value still submits when the select is disabled --}}
        <input type="hidden" name="book_id" value="{{ $currentBookId }}">

        {{-- Disabled select: visible only, value is locked --}}
        <select id="book_id"
                disabled
                class="w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-500 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
            @foreach ($books as $book)
                <option value="{{ $book->id }}" @selected($currentBookId == $book->id)>
                    {{ $book->title }}{{ $book->isbn ? " ({$book->isbn})" : '' }}
                </option>
            @endforeach
        </select>

        <p class="mt-1 text-xs text-gray-500">
            The book cannot be changed after upload. Delete the file and create a new one if needed.
        </p>
    @else
        {{-- Create mode: normal select --}}
        <select id="book_id" name="book_id" required
                class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm dark:border-gray-700 dark:text-white">
            <option value="">Select a book</option>
            @foreach ($books as $book)
                <option value="{{ $book->id }}" @selected($currentBookId == $book->id)>
                    {{ $book->title }}{{ $book->isbn ? " ({$book->isbn})" : '' }}
                </option>
            @endforeach
        </select>
    @endif

    @error('book_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

<div>
    <label for="file" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">File {{ $ebookFile ? '(leave empty to keep current file)' : '' }}</label>
    <input id="file" name="file" type="file" accept=".pdf,.epub,.mobi" {{ $ebookFile ? '' : 'required' }} class="block w-full text-sm text-gray-600 dark:text-gray-300">
    <p class="mt-1 text-xs text-gray-500">PDF, EPUB, or MOBI; maximum 50 MB.</p>
    @error('file') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
</div>

<label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300"><input name="is_primary" type="checkbox" value="1" @checked(old('is_primary', $ebookFile?->is_primary)) class="rounded border-gray-300 text-brand-500"> Use as the primary file for this book</label>
