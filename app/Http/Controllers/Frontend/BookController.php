<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display a listing of published books.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $categoryId = $request->integer('category');
        $sort = $request->string('sort', 'latest')->toString();

        $books = Book::query()
            ->with(['authors:id,name', 'category:id,name,slug'])
            ->published() // 👈 local scope — see model note below
            ->when($search, fn($q) => $q->where(function ($qq) use ($search) {
                $qq->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhereHas('authors', fn($aq) => $aq->where('name', 'like', "%{$search}%"));
            }))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->when($sort, fn($q) => match ($sort) {
                'oldest' => $q->oldest(),
                'price_asc' => $q->orderBy('price'),
                'price_desc' => $q->orderByDesc('price'),
                'title' => $q->orderBy('title'),
                'popular' => $q->orderByDesc('views'),
                default => $q->latest(),
            })
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->whereHas('books', fn($q) => $q->published())
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return view('frontend.books.index', [
            'title' => 'All Books',
            'books' => $books,
            'categories' => $categories,
            'search' => $search,
            'categoryId' => $categoryId,
            'sort' => $sort,
        ]);
    }

    /**
     * Display a single published book.
     */
    public function show(Request $request, Book $book): View
    {
        // 1. Guard: only published books are publicly viewable
        abort_unless($book->status === 'published', 404);

        // 2. Eager-load everything the show view needs in one query set
        $book->load([
            'authors:id,name,slug',
            'category:id,name,slug',
            'reviews' => fn($q) => $q->latest()->limit(10),
            'reviews.user:id,name,avatar',
            'files',
        ]);


        // 3. Increment view count (skip known bots)
        // $userAgent = strtolower($request->userAgent() ?? '');

        // $bots = [
        //     'bot',
        //     'crawl',
        //     'spider',
        //     'slurp',
        //     'bingpreview',
        //     'facebookexternalhit',
        //     'whatsapp',
        //     'telegram',
        //     'slack',
        //     'discord',
        //     'googlebot',
        //     'ahrefs',
        //     'semrush',
        // ];

        // $isBot = false;
        // foreach ($bots as $bot) {
        //     if (str_contains($userAgent, $bot)) {
        //         $isBot = true;
        //         break;
        //     }
        // }

        // if (!$isBot) {
        //     $book->increment('views');
        // }

        // 4. Related books — same category, published, excluding current
        $relatedBooks = Book::query()
            ->with(['authors:id,name', 'category:id,name,slug'])
            ->published()
            ->where('category_id', $book->category_id)
            ->whereKeyNot($book->id)
            ->latest()
            ->limit(4)
            ->get();

        // 5. Fallback: if fewer than 4, fill with other published books
        if ($relatedBooks->count() < 4) {
            $needed = 4 - $relatedBooks->count();

            $extra = Book::query()
                ->with(['authors:id,name', 'category:id,name,slug'])
                ->published()
                ->whereKeyNot($book->id)
                ->whereNotIn('id', $relatedBooks->pluck('id'))
                ->latest()
                ->limit($needed)
                ->get();

            $relatedBooks = $relatedBooks->concat($extra);
        }

        return view('frontend.books.show', [
            'title' => $book->title,
            'metaTitle' => $book->title . ' | ' . config('app.name'),
            'metaDesc' => $book->description
                ? \Str::limit(strip_tags($book->description), 160)
                : null,
            'ogImage' => $book->cover_url,
            'book' => $book,
            'relatedBooks' => $relatedBooks,
        ]);
    }
}