<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredBooks = Book::with(['authors', 'category'])
            ->published()
            ->featured()
            ->latest()
            ->take(8)
            ->get();

        $newArrivals = Book::with(['authors', 'category'])
            ->published()
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::withCount('books')
            ->orderByDesc('books_count')
            ->take(8)
            ->get();

        $totalBooks = Book::published()->count();
        $totalUsers = User::count();
        $totalAuthors = Author::count();
        $totalBorrowings = class_exists(Borrowing::class) ? Borrowing::count() : 0;

        // Testimonials — replace with real data once you have a reviews/testimonials model.
        // For now, hardcoded examples so the section renders.
        $testimonials = collect([
            (object) [
                'rating' => 5,
                'comment' => 'The Digital Library has completely changed how I read. Access to thousands of books from anywhere!',
                'user' => (object) [
                    'name' => 'Sarah Johnson',
                    'avatar' => null,
                    'role' => 'Member',
                ],
            ],
            (object) [
                'rating' => 5,
                'comment' => 'Amazing collection and the borrowing process is so simple. Highly recommended.',
                'user' => (object) [
                    'name' => 'Michael Chen',
                    'avatar' => null,
                    'role' => 'Member',
                ],
            ],
            (object) [
                'rating' => 4,
                'comment' => 'A wonderful resource for students and researchers. I use it almost every week.',
                'user' => (object) [
                    'name' => 'Amara Diallo',
                    'avatar' => null,
                    'role' => 'Member',
                ],
            ],
        ]);

        return view('frontend.home.index', compact(
            'featuredBooks',
            'newArrivals',
            'categories',
            'testimonials',
            'totalBooks',
            'totalUsers',
            'totalAuthors',
            'totalBorrowings',
        ));
    }
}