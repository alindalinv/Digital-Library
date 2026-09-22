<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['book_id' => ['required', 'exists:books,id']]);

        return $this->borrowBook(Book::findOrFail($data['book_id']));
    }

    public function borrowBook(Book $book): RedirectResponse
    {
        abort_unless($book->status === 'published', 404);

        $alreadyRequested = Borrowing::where('user_id', Auth::id())
            ->where('book_id', $book->getKey())
            ->whereIn('status', ['pending', 'approved', 'overdue'])
            ->exists();

        if ($alreadyRequested) {
            return back()->withErrors(['book_id' => 'You already have an active borrowing request for this book.']);
        }

        $created = DB::transaction(function () use ($book) {
            $lockedBook = Book::whereKey($book->getKey())->lockForUpdate()->firstOrFail();

            if ($lockedBook->stock < 1) {
                return false;
            }

            Borrowing::create([
                'user_id' => Auth::id(),
                'book_id' => $lockedBook->getKey(),
                'status' => 'pending',
                'borrowed_at' => now(),
                'due_at' => now()->addDays(14),
            ]);

            return true;
        });

        if (! $created) {
            return back()->withErrors(['book_id' => 'This book is out of stock.']);
        }

        return back()->with('success', 'Borrowing request submitted.');
    }

    public function cancel(Borrowing $borrowing): RedirectResponse
    {
        abort_unless($borrowing->user_id === Auth::id(), 403);

        if ($borrowing->status !== 'pending') {
            return back()->withErrors(['borrowing' => 'Only pending borrowing requests can be removed.']);
        }

        $borrowing->delete();

        return back()->with('success', 'Borrowing request removed.');
    }

    public function show(Borrowing $borrowing)
    {
        abort_unless($borrowing->user_id === Auth::id(), 403);

        return view('frontend.borrowings.show', compact('borrowing'));
    }
}