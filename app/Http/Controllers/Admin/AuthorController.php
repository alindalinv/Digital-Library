<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthorController extends Controller
{
    /**
     * Display a listing of authors.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));

        $authors = Author::query()
            ->withCount('books')
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('bio', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.authors.index', [
            'title' => 'Authors',
            'authors' => $authors,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new author.
     */
    public function create(): View
    {
        return view('admin.authors.create', [
            'title' => 'Create Author',
        ]);
    }

    /**
     * Store a newly created author.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:authors,name',
            ],
            'bio' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $author = Author::create([
            'name' => trim($validated['name']),
            'bio' => $validated['bio'] ?? null,
        ]);

        return redirect()
            ->route('admin.authors.show', $author)
            ->with('status', 'author-created')
            ->with(
                'success',
                "'{$author->name}' author created successfully."
            );
    }

    /**
     * Display the specified author.
     */
    public function show(Author $author): View
    {
        $author->load([
            'books' => function ($query) {
                $query
                    ->select([
                        'books.id',
                        'books.title',
                        'books.cover_image',
                        'books.created_at',
                    ])
                    ->latest();
            },
        ]);

        return view('admin.authors.show', [
            'title' => "Author: {$author->name}",
            'author' => $author,
        ]);
    }

    /**
     * Show the form for editing the specified author.
     */
    public function edit(Author $author): View
    {
        return view('admin.authors.edit', [
            'title' => "Edit Author: {$author->name}",
            'author' => $author,
        ]);
    }

    /**
     * Update the specified author.
     */
    public function update(
        Request $request,
        Author $author
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('authors', 'name')
                    ->ignore($author->getKey()),
            ],
            'bio' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $author->update([
            'name' => trim($validated['name']),
            'bio' => $validated['bio'] ?? null,
        ]);

        return redirect()
            ->route('admin.authors.show', $author)
            ->with('status', 'author-updated')
            ->with(
                'success',
                "'{$author->name}' author updated successfully."
            );
    }

    /**
     * Remove the specified author.
     */
    public function destroy(Author $author): RedirectResponse
    {
        $bookCount = $author->books()->count();

        if ($bookCount > 0) {
            return back()
                ->with(
                    'error',
                    "Cannot delete '{$author->name}' because {$bookCount} "
                    . str('book')->plural($bookCount)
                    . ' assigned to this author.'
                );
        }

        $authorName = $author->name;

        $author->delete();

        return redirect()
            ->route('admin.authors.index')
            ->with('status', 'author-deleted')
            ->with(
                'success',
                "'{$authorName}' author deleted successfully."
            );
    }
}