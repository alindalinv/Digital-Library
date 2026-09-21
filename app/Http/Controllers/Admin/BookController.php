<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Mews\Purifier\Facades\Purifier;
class BookController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status', 'all')->toString();
        $featured = $request->boolean('featured');
        $books = Book::query()
            ->with(['category', 'publisher', 'authors'])
            ->when($search, fn($q) => $q->where(function ($qq) use ($search) {
                $qq->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            }))
            ->when($status && $status !== 'all', fn($q) => $q->where('status', $status))
            ->when($featured, fn($q) => $q->where('is_featured', true))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // AJAX response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('admin.pages.books._results', [
                    'books' => $books,
                    'search' => $search,
                    'status' => $status,
                    'featured' => $featured,
                ])->render(),
                'total' => $books->total(),
            ]);
        }

        return view('admin.pages.books.index', [
            'title' => 'Books',
            'books' => $books,
            'search' => $search,
            'status' => $status,
            'featured' => $featured,
        ]);
    }

    public function trashed(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->toString();

        $books = Book::onlyTrashed()
            ->with(['category', 'authors'])
            ->when($search, fn($q) => $q->where(function ($qq) use ($search) {
                $qq->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            }))
            ->latest('deleted_at')
            ->paginate(10)
            ->withQueryString();

        // AJAX response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('admin.pages.books._trashed_results', [
                    'books' => $books,
                    'search' => $search,
                ])->render(),
                'total' => $books->total(),
            ]);
        }

        return view('admin.pages.books.trashed', [
            'title' => 'Trashed Books',
            'books' => $books,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.pages.books.create', [
            'title' => 'Create Book',
            'categories' => Category::where('status', true)->orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
            'publishers' => Publisher::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $validated['slug'] = $this->uniqueSlug($validated['slug'] ?? $validated['title']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['description'] = $validated['description']
            ? Purifier::clean($validated['description'])
            : null;
        $authorIds = $validated['authors'] ?? [];
        unset($validated['authors']);

        $book = DB::transaction(function () use ($request, $validated, $authorIds) {
            if ($request->hasFile('cover_image')) {
                $validated['cover_image'] = $this->handleCoverUpload($request);
            }

            $book = Book::create($validated);
            $book->authors()->sync($authorIds);

            return $book;
        });

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Book created successfully.');
    }

    public function show(Book $book): View
    {
        $book->load(['category', 'publisher', 'authors', 'files', 'reviews.user']);

        return view('admin.pages.books.show', [
            'title' => "Book: {$book->title}",
            'book' => $book,
        ]);
    }

    public function edit(Book $book): View
    {
        $book->load('authors');

        return view('admin.pages.books.edit', [
            'title' => "Edit Book: {$book->title}",
            'book' => $book,
            'categories' => Category::where('status', true)->orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
            'publishers' => Publisher::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate($this->rules($book));
        $validated['slug'] = $this->uniqueSlug(
            $validated['slug'] ?? $validated['title'],
            $book->id
        );
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['description'] = $validated['description']
            ? Purifier::clean($validated['description'])
            : null;
        $authorIds = $validated['authors'] ?? [];
        unset($validated['authors']);

        DB::transaction(function () use ($request, $validated, $authorIds, $book) {
            if ($request->hasFile('cover_image')) {
                $validated['cover_image'] = $this->handleCoverUpload($request, $book);
            }

            $book->update($validated);
            $book->authors()->sync($authorIds);
        });

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Book moved to trash.');
    }

    public function restore(int $id): RedirectResponse|JsonResponse
    {
        $book = Book::onlyTrashed()->findOrFail($id);
        $book->restore();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Book restored.']);
        }

        return redirect()
            ->route('admin.books.trashed')
            ->with('success', 'Book restored successfully.');
    }

    public function forceDelete(int $id): RedirectResponse|JsonResponse
    {
        $book = Book::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($book) {
            $book->authors()->detach();

            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $book->forceDelete();
        });

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Book permanently deleted.']);
        }

        return redirect()
            ->route('admin.books.trashed')
            ->with('success', 'Book permanently deleted.');
    }

    /* -----------------------------------------------------------------
     |  Helpers
     | ----------------------------------------------------------------- */

    protected function rules(?Book $book = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('books', 'slug')->ignore($book?->id)
            ],
            'isbn' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('books', 'isbn')->ignore($book?->id)
            ],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'category_id' => ['required', 'exists:categories,id'],
            'publisher_id' => ['nullable', 'exists:publishers,id'],
            'published_year' => ['nullable', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'language' => ['nullable', 'string', 'max:50'],
            'pages' => ['nullable', 'integer', 'min:1'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'authors' => ['nullable', 'array'],
            'authors.*' => ['exists:authors,id'],
        ];
    }

    protected function handleCoverUpload(Request $request, ?Book $book = null): string
    {
        if ($book?->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        return $request->file('cover_image')->store('books/covers', 'public');
    }

    protected function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $i = 2;

        while (
            Book::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'], // 5MB
        ]);

        $path = $request->file('file')->store('books/description-images', 'public');

        return response()->json([
            'location' => Storage::disk('public')->url($path),
        ]);
    }
}