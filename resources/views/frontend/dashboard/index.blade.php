<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Dashboard') }}
            </h2>
            <span class="text-muted small">
                {{ now()->format('l, F j, Y') }}
            </span>
        </div>
    </x-slot>

    @php
        $user = auth()->user();

        $activeBorrowings = $user->borrowings()
            ->whereNull('returned_at')
            ->with('book')
            ->latest()
            ->get();

        $totalBorrowed = $user->borrowings()->count();
        $totalReturned = $user->borrowings()->whereNotNull('returned_at')->count();
        $totalReviews = $user->reviews()->count();
        $overdueCount = $activeBorrowings->filter(fn($b) => $b->due_at && $b->due_at->isPast())->count();
    @endphp

    <div class="py-5">
        <div class="container">

            {{-- ============ Welcome Banner ============ --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-4 p-md-5 bg-primary text-white position-relative">
                    <div class="row align-items-center g-4">
                        <div class="col-md-8">
                            <h3 class="fs-3 fw-bold mb-2">
                                Welcome back, {{ $user->name }}! 👋
                            </h3>
                            <p class="text-white-50 mb-3">
                                You're logged in to the Digital Library system.
                                @if($overdueCount > 0)
                                    <span class="badge bg-warning text-dark ms-2">
                                        {{ $overdueCount }} overdue {{ Str::plural('book', $overdueCount) }}
                                    </span>
                                @endif
                            </p>

                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('books.index') }}" class="btn btn-light text-primary fw-semibold">
                                    <i class="fas fa-book me-1"></i> Browse Books
                                </a>
                                <a href="{{ route('borrowings.index') }}" class="btn btn-outline-light fw-semibold">
                                    <i class="fas fa-bookmark me-1"></i> My Borrowings
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4 text-md-end d-none d-md-block">
                            <div style="font-size: 6rem; line-height: 1;">📖</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ Roles Alert ============ --}}
            @if($user->roles && $user->roles->isNotEmpty())
                <div class="alert alert-primary border-start border-4 border-primary d-flex align-items-start mb-4"
                    role="alert">
                    <div class="flex-shrink-0 me-3">
                        <svg style="width: 1.25rem; height: 1.25rem;" class="text-primary" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="small mb-0 text-primary-emphasis">
                            <strong>Roles:</strong> {{ $user->getRoleNames()->join(', ') }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- ============ Stats Cards ============ --}}
            <div class="row g-3 mb-4">
                <div class="col-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="bg-success-subtle text-success rounded-3 p-3">
                                    <i class="fas fa-book-reader fs-4"></i>
                                </div>
                            </div>
                            <h4 class="fs-2 fw-bold text-dark mb-1">{{ $activeBorrowings->count() }}</h4>
                            <p class="text-secondary small mb-0">Currently Borrowed</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="bg-primary-subtle text-primary rounded-3 p-3">
                                    <i class="fas fa-check-circle fs-4"></i>
                                </div>
                            </div>
                            <h4 class="fs-2 fw-bold text-dark mb-1">{{ $totalReturned }}</h4>
                            <p class="text-secondary small mb-0">Books Returned</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="bg-warning-subtle text-warning rounded-3 p-3">
                                    <i class="fas fa-star fs-4"></i>
                                </div>
                            </div>
                            <h4 class="fs-2 fw-bold text-dark mb-1">{{ $totalReviews }}</h4>
                            <p class="text-secondary small mb-0">Reviews Written</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="bg-secondary-subtle text-secondary rounded-3 p-3">
                                    <i class="fas fa-calendar-alt fs-4"></i>
                                </div>
                            </div>
                            <h4 class="fs-5 fw-bold text-dark mb-1">
                                {{ $user->created_at->format('M Y') }}
                            </h4>
                            <p class="text-secondary small mb-0">Member Since</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ Recent Borrowings ============ --}}
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div
                            class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold text-dark mb-0">
                                <i class="fas fa-history text-primary me-2"></i> Recent Borrowings
                            </h5>
                            <a href="{{ route('borrowings.index') }}"
                                class="text-primary text-decoration-none small fw-semibold">
                                View All <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>

                        <div class="card-body p-4">
                            @if($activeBorrowings->isEmpty())
                                <div class="text-center py-5">
                                    <div class="fs-1 mb-3">📚</div>
                                    <h6 class="fw-semibold text-dark">No active borrowings</h6>
                                    <p class="text-secondary small mb-3">
                                        Start your reading journey by borrowing a book.
                                    </p>
                                    <a href="{{ route('books.index') }}" class="btn btn-primary btn-sm fw-semibold">
                                        Browse Books
                                    </a>
                                </div>
                            @else
                                <div class="list-group list-group-flush">
                                    @foreach($activeBorrowings->take(5) as $borrowing)
                                                            @php
                                                                $isOverdue = $borrowing->due_at && $borrowing->due_at->isPast();
                                                            @endphp
                                                            <div class="list-group-item px-0 py-3 border-0 border-bottom">
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <img src="{{ $borrowing->book->cover_image
                                        ? asset('storage/' . $borrowing->book->cover_image)
                                        : asset('assets/frontend/images/default-book.png') }}"
                                                                        alt="{{ $borrowing->book->title }}" width="48" height="64"
                                                                        class="rounded object-fit-cover shadow-sm">

                                                                    <div class="flex-grow-1 min-w-0">
                                                                        <h6 class="fw-semibold text-dark mb-1">
                                                                            {{ $borrowing->book->title }}
                                                                        </h6>
                                                                        <p class="text-secondary small mb-0">
                                                                            Borrowed {{ $borrowing->created_at->diffForHumans() }}
                                                                        </p>
                                                                    </div>

                                                                    <div class="text-end">
                                                                        @if($isOverdue)
                                                                            <span class="badge bg-danger-subtle text-danger-emphasis">
                                                                                Overdue
                                                                            </span>
                                                                            <p class="text-danger small mb-0 mt-1">
                                                                                {{ $borrowing->due_at->diffForHumans() }}
                                                                            </p>
                                                                        @elseif($borrowing->due_at)
                                                                            <span class="badge bg-success-subtle text-success-emphasis">
                                                                                Active
                                                                            </span>
                                                                            <p class="text-secondary small mb-0 mt-1">
                                                                                Due {{ $borrowing->due_at->diffForHumans() }}
                                                                            </p>
                                                                        @else
                                                                            <span class="badge bg-primary-subtle text-primary-emphasis">
                                                                                Active
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ============ Quick Actions ============ --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="fw-bold text-dark mb-0">
                                <i class="fas fa-bolt text-warning me-2"></i> Quick Actions
                            </h5>
                        </div>

                        <div class="card-body p-4">
                            <div class="d-grid gap-2">
                                <a href="{{ route('books.index') }}"
                                    class="btn btn-outline-primary text-start fw-semibold py-3">
                                    <i class="fas fa-search me-2"></i> Find a Book
                                </a>

                                <a href="{{ route('borrowings.index') }}"
                                    class="btn btn-outline-success text-start fw-semibold py-3">
                                    <i class="fas fa-bookmark me-2"></i> My Borrowings
                                </a>

                                <a href="{{ route('profile.edit') }}"
                                    class="btn btn-outline-secondary text-start fw-semibold py-3">
                                    <i class="fas fa-user-cog me-2"></i> Edit Profile
                                </a>
                            </div>

                            <hr class="my-4">

                            <div class="bg-light rounded-3 p-3">
                                <h6 class="fw-semibold text-dark small mb-2">
                                    <i class="fas fa-lightbulb text-warning me-1"></i> Reading Tip
                                </h6>
                                <p class="text-secondary small mb-0">
                                    Reading 20 minutes a day adds up to <strong>120+ hours</strong> a year.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>