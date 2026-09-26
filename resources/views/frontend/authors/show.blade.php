<x-app-layout>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-semibold fs-4 text-dark mb-1">
                    {{ $author->name }}
                </h2>

                <div class="small text-muted">
                    <a
                        href="{{ route('home') }}"
                        class="text-decoration-none text-muted"
                    >
                        Home
                    </a>

                    <span class="mx-2">/</span>

                    <span>Author</span>
                </div>
            </div>

            <a
                href="{{ url()->previous() }}"
                class="btn btn-sm btn-outline-secondary"
            >
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>
        </div>
    </x-slot>

    <div class="container py-4">

        {{-- Author Profile --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 p-md-5">

                <div class="row align-items-start g-4">

                    {{-- Author Icon --}}
                    <div class="col-auto">
                        <div
                            class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white"
                            style="width: 90px; height: 90px;"
                        >
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                    </div>

                    {{-- Author Information --}}
                    <div class="col">
                        <h1 class="fw-bold text-dark mb-2">
                            {{ $author->name }}
                        </h1>

                        <div class="text-muted small mb-3">
                            <i class="fas fa-book me-1"></i>
                            {{ $books->count() }}
                            {{ \Illuminate\Support\Str::plural('book', $books->count()) }}
                        </div>

                        @if ($author->bio)
                            <div class="text-secondary lh-lg">
                                {!! nl2br(e($author->bio)) !!}
                            </div>
                        @else
                            <p class="text-muted fst-italic mb-0">
                                No biography is available for this author yet.
                            </p>
                        @endif
                    </div>

                </div>

            </div>
        </div>


        {{-- Books by Author --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    Books by {{ $author->name }}
                </h3>

                <p class="text-muted small mb-0">
                    Browse books written by this author.
                </p>
            </div>

            <span class="badge bg-light text-dark border">
                {{ $books->count() }} {{ \Illuminate\Support\Str::plural('book', $books->count()) }}
            </span>
        </div>


        @if ($books->isNotEmpty())

            <div class="row g-4">

                @foreach ($books as $book)

                    <div class="col-6 col-md-4 col-lg-3">

                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">

                            {{-- Book Cover --}}
                            <a
                                href="{{ route('books.show', $book) }}"
                                class="text-decoration-none"
                            >
                                <div
                                    class="bg-light d-flex align-items-center justify-content-center"
                                    style="height: 280px;"
                                >
                                    @if ($book->cover_image)
                                        <img
                                            src="{{ asset('storage/' . $book->cover_image) }}"
                                            alt="{{ $book->title }}"
                                            class="w-100 h-100 object-fit-cover"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-book fa-3x mb-2"></i>
                                            <div class="small">
                                                No Cover
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </a>

                            {{-- Book Information --}}
                            <div class="card-body d-flex flex-column p-3">

                                <h5 class="fw-semibold text-dark mb-2">
                                    <a
                                        href="{{ route('books.show', $book) }}"
                                        class="text-dark text-decoration-none"
                                    >
                                        {{ $book->title }}
                                    </a>
                                </h5>

                                @if ($book->created_at)
                                    <div class="small text-muted mt-auto">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $book->created_at->format('M j, Y') }}
                                    </div>
                                @endif

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            {{-- Empty State --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center py-5">

                    <div
                        class="d-flex align-items-center justify-content-center rounded-circle bg-light text-muted mx-auto mb-3"
                        style="width: 70px; height: 70px;"
                    >
                        <i class="fas fa-book fa-xl"></i>
                    </div>

                    <h5 class="fw-semibold text-dark mb-2">
                        No books found
                    </h5>

                    <p class="text-muted mb-4">
                        There are currently no books associated with
                        {{ $author->name }}.
                    </p>

                    <a
                        href="{{ route('books.index') }}"
                        class="btn btn-primary"
                    >
                        <i class="fas fa-book me-1"></i>
                        Browse All Books
                    </a>

                </div>
            </div>

        @endif

    </div>

</x-app-layout>