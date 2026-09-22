<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 fw-semibold text-dark">
            {{ __('Welcome to Digital Library') }}
        </h2>
    </x-slot>

    @php
        $totalBooks      = \App\Models\Book::published()->count();
        $totalUsers      = \App\Models\User::where('status', true)->count();
        $totalBorrowings = \App\Models\Borrowing::count();
        $totalAuthors    = \App\Models\Author::count();

        $featuredBooks = \App\Models\Book::with(['authors', 'category'])
            ->featured()
            ->latest()
            ->take(8)
            ->get();

        $newArrivals = \App\Models\Book::with(['authors', 'category'])
            ->published()
            ->latest()
            ->take(8)
            ->get();

        $categories = \App\Models\Category::withCount(['books' => fn ($query) => $query->published()])
            ->having('books_count', '>', 0)
            ->orderByDesc('books_count')
            ->take(8)
            ->get();

        $testimonials = \App\Models\Review::with('user')
            ->where('rating', '>=', 4)
            ->latest()
            ->take(6)
            ->get();
    @endphp

    <style>
        .home-hero { background: #1456a0; }
        .home-hero-panel { max-width: 42rem; }
        .home-section { padding-block: 4.5rem; }
        .home-section-muted { background: #f5f8fc; }
        .home-section-title { letter-spacing: -.02em; }
        .home-book-grid > [class*="col-"] { display: flex; }
        .home-book-grid .catalog-book-card { width: 100%; }
        .home-book-grid .catalog-cover { height: 260px; object-fit: cover; }
        .home-book-grid .catalog-book-card .card-body { min-height: 205px; }
        .home-category-card { transition: transform .2s ease, box-shadow .2s ease; }
        .home-category-card:hover { transform: translateY(-4px); box-shadow: 0 1rem 2rem rgba(31, 51, 73, .12) !important; }
        .home-stat { border-left: 3px solid rgba(255,255,255,.55); }
        .home-quote { border-top: 3px solid #0d6efd; }
    </style>

    <!-- Hero Section -->
    <section class="home-hero py-5 text-white position-relative overflow-hidden">
        <div class="container py-5">
            <div class="row align-items-center g-5">

                <div class="col-md-7">
                    <div class="home-hero-panel">
                    <p class="text-uppercase fw-semibold small mb-3 text-white-50">Your reading shelf, online</p>
                    <h1 class="display-4 fw-bold mb-4">
                        Discover Your Next Great Read
                    </h1>

                    <p class="lead text-white-50 mb-4">
                        Explore {{ number_format($totalBooks) }}+ books, audiobooks, and more.
                        Join our community of {{ number_format($totalUsers) }}+ book lovers today!
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('books.index') }}"
                           class="btn btn-light btn-lg text-primary fw-semibold">
                            Browse Books
                        </a>

                        @guest
                            <a href="{{ route('register') }}"
                               class="btn btn-outline-light btn-lg fw-semibold">
                                Get Started
                            </a>
                        @endguest
                    </div>
                    </div>
                </div>

                <div class="col-md-5 d-none d-md-block">
                    <div class="border border-white border-opacity-25 rounded-4 p-4 bg-white bg-opacity-10">
                        <p class="small text-uppercase fw-semibold text-white-50 mb-3">Inside the collection</p>
                        <div class="d-flex align-items-end gap-3">
                            <div class="display-3 fw-bold">{{ number_format($totalBooks) }}</div>
                            <div class="pb-2 text-white-50">published titles<br>ready to explore</div>
                        </div>
                        <hr class="border-white opacity-25">
                        <a href="{{ route('books.index', ['featured' => 1]) }}" class="link-light text-decoration-none fw-semibold">Browse featured books <span aria-hidden="true">→</span></a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Featured Books Section -->
    @if($featuredBooks->isNotEmpty())
        <section class="home-section home-section-muted">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="home-section-title h2 fw-bold text-dark mb-1">Featured Books</h2>
                        <p class="text-secondary mb-0">Handpicked selections for our readers</p>
                    </div>

                    <a href="{{ route('books.index') }}"
                       class="text-primary text-decoration-none fw-semibold">
                        View All
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-4 home-book-grid">
                    @foreach($featuredBooks as $book)
                        <div class="col-12 col-sm-6 col-lg-3 d-flex">
                            <x-frontend.book-card :book="$book" :featured="true" />
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    <!-- New Arrivals Section -->
    @if($newArrivals->isNotEmpty())
        <section class="home-section bg-white">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="home-section-title h2 fw-bold text-dark mb-1">New Arrivals</h2>
                        <p class="text-secondary mb-0">Fresh titles added to our collection</p>
                    </div>

                    <a href="{{ route('books.index') }}"
                       class="text-primary text-decoration-none fw-semibold">
                        View All
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-4 home-book-grid">
                    @foreach($newArrivals as $book)
                        <div class="col-12 col-sm-6 col-lg-3 d-flex">
                            <x-frontend.book-card :book="$book" />
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    <!-- Categories Section -->
    @if($categories->isNotEmpty())
        <section class="home-section home-section-muted">
            <div class="container">

                <div class="text-center mb-5">
                    <h2 class="home-section-title h2 fw-bold text-dark">Browse by Category</h2>
                    <p class="text-secondary">Find books that match your interests</p>
                </div>

                <div class="row g-4">
                    @foreach($categories as $category)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('books.index', ['category' => $category->id]) }}"
                               class="text-decoration-none">
                                <div class="home-category-card card h-100 border-0 shadow-sm bg-white text-dark">
                                    <div class="card-body p-4 text-center">
                                        <div class="fs-1 mb-2 text-primary">▦</div>
                                        <h3 class="h5 fw-semibold mb-2">{{ $category->name }}</h3>
                                        <p class="text-secondary small mb-0">
                                            {{ $category->books_count }} {{ Str::plural('book', $category->books_count) }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    <!-- Stats Section -->
    <section class="home-section bg-primary text-white">
        <div class="container py-4">
            <div class="row g-4 text-center">

                    <div class="col-6 col-md-3"><div class="home-stat ps-3">
                    <div class="display-5 fw-bold">{{ number_format($totalBooks) }}+</div>
                    <div class="text-white-50 mt-2">Books Available</div>
                    </div></div>

                    <div class="col-6 col-md-3"><div class="home-stat ps-3">
                    <div class="display-5 fw-bold">{{ number_format($totalUsers) }}+</div>
                    <div class="text-white-50 mt-2">Active Readers</div>
                    </div></div>

                    <div class="col-6 col-md-3"><div class="home-stat ps-3">
                    <div class="display-5 fw-bold">{{ number_format($totalBorrowings) }}+</div>
                    <div class="text-white-50 mt-2">Books Borrowed</div>
                    </div></div>

                    <div class="col-6 col-md-3"><div class="home-stat ps-3">
                    <div class="display-5 fw-bold">{{ number_format($totalAuthors) }}+</div>
                    <div class="text-white-50 mt-2">Authors</div>
                    </div></div>

            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    @if($testimonials->isNotEmpty())
        <section class="home-section home-section-muted">
            <div class="container py-4">

                <div class="text-center mb-5">
                    <h2 class="display-6 fw-bold text-dark">What Our Readers Say</h2>
                    <p class="text-secondary mb-0">Join thousands of satisfied readers</p>
                </div>

                <div class="row g-4">
                    @foreach($testimonials as $testimonial)
                        <div class="col-12 col-md-4">
                            <div class="home-quote card h-100 border-0 shadow-sm">
                                <div class="card-body p-4">

                                    <div class="mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= ($testimonial->rating ?? 5) ? 'text-warning' : 'text-secondary' }}"></i>
                                        @endfor
                                    </div>

                                    <p class="text-secondary fst-italic">
                                        "{{ $testimonial->comment ?? '' }}"
                                    </p>

                                    @php
                                        $user = $testimonial->user ?? null;
                                        $userName = $user->name ?? 'Anonymous';
                                        $userAvatar = $user && ($user->avatar ?? null)
                                            ? $user->avatar
                                            : asset('assets/frontend/images/default-avatar.png');
                                        $userRole = $user->role ?? 'Reader';
                                    @endphp

                                    <div class="d-flex align-items-center mt-4">
                                        <img src="{{ $userAvatar }}"
                                             alt="{{ $userName }}"
                                             width="40" height="40"
                                             class="rounded-circle me-3">

                                        <div>
                                            <p class="fw-semibold text-dark mb-0">{{ $userName }}</p>
                                            <p class="small text-secondary mb-0">{{ $userRole }}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    <!-- Call to Action Section -->
    <section class="home-section bg-primary text-white">
        <div class="container py-5 text-center">

            <h2 class="display-6 fw-bold mb-3">
                Ready to Start Your Reading Journey?
            </h2>

            <p class="lead text-white-50 mb-4">
                Join our community and get access to {{ number_format($totalBooks) }}+ books
            </p>

            @guest
                <a href="{{ route('register') }}"
                   class="btn btn-light btn-lg text-primary fw-bold">
                    Create Free Account
                </a>
            @else
                <a href="{{ route('books.index') }}"
                   class="btn btn-light btn-lg text-primary fw-bold">
                    Start Reading Now
                </a>
            @endguest

        </div>
    </section>

</x-app-layout>