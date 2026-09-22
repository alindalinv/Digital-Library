<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:borrowings.view')->only(['index', 'show']);
        $this->middleware('permission:borrowings.approve')->only('approve');
    }

    public function index(Request $request): View|JsonResponse
    {
        return $this->listing($request);
    }

    public function pending(Request $request): View|JsonResponse
    {
        return $this->listing($request, 'pending');
    }

    private function listing(Request $request, ?string $forcedStatus = null): View|JsonResponse
    {
        $search = $request->string('search')->trim()->toString();
        $status = $forcedStatus ?? $request->string('status', 'all')->toString();
        $allowedStatuses = ['all', 'pending', 'approved', 'rejected', 'returned', 'overdue'];
        if (! in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        $borrowings = Borrowing::with(['user:id,name,email', 'book:id,title,slug,cover_image'])
            ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                $query->whereHas('user', fn ($user) => $user
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"))
                    ->orWhereHas('book', fn ($book) => $book->where('title', 'like', "%{$search}%"));
            }))
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('admin.borrowings._results', compact('borrowings'))->render(),
                'pagination' => (string) $borrowings->links(),
                'total' => $borrowings->total(),
            ]);
        }

        return view('admin.borrowings.index', compact('borrowings', 'search', 'status') + [
            'title' => $forcedStatus === 'pending' ? 'Pending Approvals' : 'Borrowings',
            'pendingCount' => Borrowing::where('status', 'pending')->count(),
            'forcedStatus' => $forcedStatus,
        ]);
    }

    public function approve(Borrowing $borrowing)
    {
        abort_unless($borrowing->status === 'pending', 422, 'Only pending requests can be approved.');

        $approved = DB::transaction(function () use ($borrowing) {
            $book = Book::whereKey($borrowing->book_id)->lockForUpdate()->firstOrFail();

            if ($book->stock < 1) {
                return false;
            }

            $borrowing->update([
                'status' => 'approved',
                'borrowed_at' => now(),
                'due_at' => now()->addDays(14),
            ]);
            $book->decrement('stock');

            return true;
        });

        if (! $approved) {
            return back()->with('error', 'This book is out of stock.');
        }

        return back()->with('success', 'Borrowing approved.');
    }

    /*
    public function indexLegacy()
    {
        $borrowings = Borrowing::with(['user', 'book'])
            ->latest()
            ->paginate(15);

        return view('admin.borrowings.index', compact('borrowings'));
    }
    */
}