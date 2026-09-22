<x-app-layout title="My Borrowings">
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
            <div>
                <p class="small text-uppercase fw-semibold text-primary mb-1">Your reading shelf</p>
                <h1 class="h4 mb-0 fw-semibold text-dark">My Borrowings</h1>
            </div>
            <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm">Browse books</a>
        </div>
    </x-slot>

    <style>
        .borrowing-row { transition: background-color .15s ease; }
        .borrowing-row:hover { background: #f8fbff; }
        .borrowing-cover { width: 52px; height: 72px; object-fit: cover; }
    </style>

    <div class="py-5">
        <div class="container">
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100"><div class="card-body p-3"><p class="small text-secondary mb-1">Total records</p><p class="h4 fw-bold text-dark mb-0">{{ $borrowings->total() }}</p></div></div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100"><div class="card-body p-3"><p class="small text-secondary mb-1">On this page</p><p class="h4 fw-bold text-primary mb-0">{{ $borrowings->count() }}</p></div></div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm h-100 bg-primary text-white"><div class="card-body p-3 d-flex align-items-center justify-content-between"><div><p class="small text-white-50 mb-1">Keep reading</p><p class="fw-semibold mb-0">Find something new for your shelf.</p></div><svg class="text-white opacity-75" width="38" height="38" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></svg></div></div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 px-4 py-4 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                    <div><h2 class="h5 fw-bold text-dark mb-1">Borrowing history</h2><p class="small text-secondary mb-0">Track requests, due dates, and returned books.</p></div>
                    @if($borrowings->hasPages())<span class="small text-secondary">{{ $borrowings->firstItem() }}-{{ $borrowings->lastItem() }} of {{ $borrowings->total() }}</span>@endif
                </div>

                @if($borrowings->isEmpty())
                    <div class="card-body text-center py-5 px-3">
                        <svg class="mb-3 text-primary" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.4" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></svg>
                        <h3 class="h5 fw-semibold text-dark">No borrowings yet</h3>
                        <p class="text-secondary mb-3">Explore the collection and start your reading journey.</p>
                        <a href="{{ route('books.index') }}" class="btn btn-primary">Browse books</a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light"><tr><th class="ps-4">Book</th><th>Status</th><th>Borrowed</th><th>Due</th><th class="text-end pe-4">Action</th></tr></thead>
                            <tbody>
                                @foreach($borrowings as $borrowing)
                                    @php
                                        $isOverdue = $borrowing->due_at && $borrowing->due_at->isPast() && ! $borrowing->returned_at;
                                        $statusClass = match($borrowing->status) { 'approved' => 'bg-success-subtle text-success-emphasis', 'rejected' => 'bg-danger-subtle text-danger-emphasis', 'returned' => 'bg-secondary-subtle text-secondary-emphasis', default => 'bg-warning-subtle text-warning-emphasis' };
                                    @endphp
                                    <tr class="borrowing-row">
                                        <td class="ps-4"><div class="d-flex align-items-center gap-3">@if ($borrowing->book->cover_url)
                                        <img src="{{ $borrowing->book->cover_url}}" alt="{{ $borrowing->book->title }}" class="borrowing-cover rounded shadow-sm">
                                        @endif<div class="min-w-0"><a href="{{ route('books.show', $borrowing->book->slug) }}" class="fw-semibold text-dark text-decoration-none">{{ $borrowing->book->title }}</a><div class="small text-secondary">{{ $borrowing->book->authors->pluck('name')->join(', ') }}</div></div></div></td>
                                        <td><span class="badge {{ $isOverdue ? 'bg-danger-subtle text-danger-emphasis' : $statusClass }}">{{ $isOverdue ? 'Overdue' : ucfirst($borrowing->status) }}</span></td>
                                        <td class="small text-secondary">{{ $borrowing->borrowed_at?->format('M d, Y') ?? '—' }}</td>
                                        <td class="small {{ $isOverdue ? 'text-danger fw-semibold' : 'text-secondary' }}">{{ $borrowing->returned_at ? 'Returned' : ($borrowing->due_at?->format('M d, Y') ?? '—') }}</td>
                                        <td class="text-end pe-4">@if($borrowing->status === 'pending')<form method="POST" action="{{ route('borrowings.cancel', $borrowing) }}" onsubmit="return confirm('Remove this borrowing request?')" class="d-inline">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Remove request</button></form>@else<span class="small text-secondary">—</span>@endif</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            @if($borrowings->hasPages())
                <div class="mt-4">{{ $borrowings->onEachSide(1)->links('frontend.pagination') }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
