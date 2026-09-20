<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $books = Book::query()
            ->with(['category', 'publisher', 'authors'])
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pages.books.index', [
            'title'  => 'Books',
            'books'  => $books,
            'search' => $search,
        ]);
    }

    /**
     * Display trashed books.
     */
    public function trashed(): View
    {
        $books = Book::onlyTrashed()
            ->with(['category', 'authors'])
            ->latest()
            ->paginate(10);

        return view('admin.pages.books.trashed', [
            'title' => 'Trashed Books',
            'books' => $books,
        ]);
    }

    /**
     * Show the form for creating a new book.
     */
    public function create(): View
    {
        return view('admin.pages.books.create', [
            'title'      => 'Create Book',
            'categories' => Category::where('status', true)->orderBy('name')->get(),
            'authors'    => Author::orderBy('name')->get(),
            'publishers' => Publisher::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created book.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'slug'           => ['nullable', 'string', 'max:255', 'unique:books,slug'],
            'isbn'           => ['nullable', 'string', 'max:20', 'unique:books,isbn'],
            'description'    => ['nullable', 'string'],
            'cover_image'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'category_id'    => ['required', 'exists:categories,id'],
            'publisher_id'   => ['nullable', 'exists:publishers,id'],
            'published_year' => ['nullable', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'language'       => ['nullable', 'string', 'max:50'],
            'pages'          => ['nullable', 'integer', 'min:1'],
            'price'          => ['nullable', 'numeric', 'min:0'],
            'stock'          => ['required', 'integer', 'min:0'],
            'is_featured'    => ['boolean'],
            'status'         => ['required', 'in:draft,published,archived'],
            'authors'        => ['nullable', 'array'],
            'authors.*'      => ['exists:authors,id'],
        ]);

        // Handle cover upload
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')
                ->store('books/covers', 'public');
        }

        // Extract authors before creating
        $authorIds = $validated['authors'] ?? [];
        unset($validated['authors']);

        $book = Book::create($validated);

        // Attach authors
        if (! empty($authorIds)) {
            $book->authors()->sync($authorIds);
        }

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book): View
    {
        $book->load(['category', 'publisher', 'authors', 'files', 'reviews.user']);

        return view('admin.pages.books.show', [
            'title' => "Book: {$book->title}",
            'book'  => $book,
        ]);
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book): View
    {
        $book->load('authors');

        return view('admin.pages.books.edit', [
            'title'      => "Edit Book: {$book->title}",
            'book'       => $book,
            'categories' => Category::where('status', true)->orderBy('name')->get(),
            'authors'    => Author::orderBy('name')->get(),
            'publishers' => Publisher::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified book.
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'slug'           => ['nullable', 'string', 'max:255', 'unique:books,slug,' . $book->id],
            'isbn'           => ['nullable', 'string', 'max:20', 'unique:books,isbn,' . $book->id],
            'description'    => ['nullable', 'string'],
            'cover_image'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'category_id'    => ['required', 'exists:categories,id'],
            'publisher_id'   => ['nullable', 'exists:publishers,id'],
            'published_year' => ['nullable', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'language'       => ['nullable', 'string', 'max:50'],
            'pages'          => ['nullable', 'integer', 'min:1'],
            'price'          => ['nullable', 'numeric', 'min:0'],
            'stock'          => ['required', 'integer', 'min:0'],
            'is_featured'    => ['boolean'],
            'status'         => ['required', 'in:draft,published,archived'],
            'authors'        => ['nullable', 'array'],
            'authors.*'      => ['exists:authors,id'],
        ]);

        // Handle cover upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $validated['cover_image'] = $request->file('cover_image')
                ->store('books/covers', 'public');
        }

        // Extract authors
        $authorIds = $validated['authors'] ?? [];
        unset($validated['authors']);

        $book->update($validated);

        // Sync authors
        $book->authors()->sync($authorIds);

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Book updated successfully.');
    }

    /**
     * Soft-delete the specified book.
     */
    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Book moved to trash.');
    }

    /**
     * Restore a soft-deleted book.
     */
    public function restore(int $id): RedirectResponse
    {
        $book = Book::onlyTrashed()->findOrFail($id);
        $book->restore();

        return redirect()
            ->route('admin.books.trashed')
            ->with('success', 'Book restored successfully.');
    }

    /**
     * Permanently delete a book.
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $book = Book::onlyTrashed()->findOrFail($id);

        // Delete cover image
        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->forceDelete();

        return redirect()
            ->route('admin.books.trashed')
            ->with('success', 'Book permanently deleted.');
    }
}