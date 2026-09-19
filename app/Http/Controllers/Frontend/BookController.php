<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(): View
    {
        $books = Book::with(['authors', 'category'])
            ->published()
            ->latest()
            ->paginate(12);

        return view('frontend.books.index', [
            'books' => $books,
            'title' => 'All Books', 
        ]);
    }

    public function show(Book $book): View
    {
        $book->load(['authors', 'category']);

        $relatedBooks = Book::with(['authors', 'category'])
            ->published()
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();

        return view('frontend.books.show', [
            'book' => $book,
            'relatedBooks' => $relatedBooks,
            'title' => $book->title,
        ]);
    }
}