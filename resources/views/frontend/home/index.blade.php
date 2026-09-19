<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 fw-semibold text-dark">
            {{ __('Welcome to Digital Library') }}
        </h2>
    </x-slot>

    <!-- Hero Section -->
    <section class="py-5 bg-primary text-white position-relative overflow-hidden">
        <div class="container py-5">
            <div class="row align-items-center g-5">

                <div class="col-md-6">
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

                <div class="col-md-6 d-none d-md-block text-center">
                    <div style="font-size: 12rem; line-height: 1;">📚</div>
                </div>

            </div>
        </div>
    </section>

    <!-- Featured Books Section -->
    @if($featuredBooks->isNotEmpty())
        <section class="py-5 bg-light">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h2 fw-bold text-dark mb-1">Featured Books</h2>
                        <p class="text-secondary mb-0">Handpicked selections for our readers</p>
                    </div>

                    <a href="{{ route('books.index') }}"
                       class="text-primary text-decoration-none fw-semibold">
                        View All
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-4">
                    @foreach($featuredBooks as $book)
                        <div class="col-12 col-sm-6 col-lg-3">
                            <x-frontend.book-card :book="$book" :featured="true" />
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    <!-- New Arrivals Section -->
    @if($newArrivals->isNotEmpty())
        <section class="py-5 bg-white">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h2 fw-bold text-dark mb-1">New Arrivals</h2>
                        <p class="text-secondary mb-0">Fresh titles added to our collection</p>
                    </div>

                    <a href="{{ route('books.index') }}"
                       class="text-primary text-decoration-none fw-semibold">
                        View All
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-4">
                    @foreach($newArrivals as $book)
                        <div class="col-12 col-sm-6 col-lg-3">
                            <x-frontend.book-card :book="$book" />
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    <!-- Categories Section -->
    @if($categories->isNotEmpty())
        <section class="py-5 bg-light">
            <div class="container">

                <div class="text-center mb-5">
                    <h2 class="h2 fw-bold text-dark">Browse by Category</h2>
                    <p class="text-secondary">Find books that match your interests</p>
                </div>

                <div class="row g-4">
                    @foreach($categories as $category)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('books.index', ['category' => $category->slug]) }}"
                               class="text-decoration-none">
                                <div class="card h-100 border-0 shadow-sm bg-primary text-white">
                                    <div class="card-body p-4 text-center">
                                        <div class="fs-1 mb-2">📚</div>
                                        <h3 class="h5 fw-semibold mb-2">{{ $category->name }}</h3>
                                        <p class="text-white-50 small mb-0">
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
    <section class="py-5 bg-primary text-white">
        <div class="container py-4">
            <div class="row g-4 text-center">

                <div class="col-6 col-md-3">
                    <div class="display-5 fw-bold">{{ number_format($totalBooks) }}+</div>
                    <div class="text-white-50 mt-2">Books Available</div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="display-5 fw-bold">{{ number_format($totalUsers) }}+</div>
                    <div class="text-white-50 mt-2">Active Readers</div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="display-5 fw-bold">{{ number_format($totalBorrowings) }}+</div>
                    <div class="text-white-50 mt-2">Books Borrowed</div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="display-5 fw-bold">{{ number_format($totalAuthors) }}+</div>
                    <div class="text-white-50 mt-2">Authors</div>
                </div>

            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    @if($testimonials->isNotEmpty())
        <section class="py-5 bg-light">
            <div class="container py-4">

                <div class="text-center mb-5">
                    <h2 class="display-6 fw-bold text-dark">What Our Readers Say</h2>
                    <p class="text-secondary mb-0">Join thousands of satisfied readers</p>
                </div>

                <div class="row g-4">
                    @foreach($testimonials as $testimonial)
                        <div class="col-12 col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
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
    <section class="py-5 bg-primary text-white">
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