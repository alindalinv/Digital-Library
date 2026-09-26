<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function show(Author $author): View
    {
        $author->load([
            'books' => function ($query) {
                $query->latest();
            },
        ]);

        return view('frontend.authors.show', [
            'author' => $author,
            'books' => $author->books,
        ]);
    }
}