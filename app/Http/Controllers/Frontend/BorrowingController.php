<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::where('user_id', Auth::id())
            ->with('book')
            ->latest()
            ->paginate(15);

        return view('frontend.borrowings.index', compact('borrowings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
        ]);

        $book = Book::findOrFail($data['book_id']);

        if ($book->stock < 1) {
            return back()->withErrors(['book_id' => 'This book is out of stock.']);
        }

        Borrowing::create([
            'user_id'     => Auth::id(),
            'book_id'     => $book->id,
            'status'      => 'pending',
            'borrowed_at' => now(),
            'due_at'      => now()->addDays(14),
        ]);

        return back()->with('success', 'Borrowing request submitted.');
    }

    public function show(Borrowing $borrowing)
    {
        abort_unless($borrowing->user_id === Auth::id(), 403);

        return view('frontend.borrowings.show', compact('borrowing'));
    }
}