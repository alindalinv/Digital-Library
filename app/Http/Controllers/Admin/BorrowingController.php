<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['user', 'book'])
            ->latest()
            ->paginate(15);

        return view('admin.borrowings.index', compact('borrowings'));
    }

    public function approve(Borrowing $borrowing)
    {
        $this->authorize('borrowings.approve');

        $borrowing->update([
            'status'      => 'approved',
            'borrowed_at' => now(),
            'due_at'      => now()->addDays(14),
        ]);

        $borrowing->book->decrement('stock');

        return back()->with('success', 'Borrowing approved.');
    }
}