<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 fw-semibold text-dark">{{ $book->title }}</h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}"
                             class="img-fluid rounded-3 shadow-sm"
                             alt="{{ $book->title }}">
                    @else
                        <div class="bg-secondary-subtle rounded-3 d-flex align-items-center justify-content-center"
                             style="aspect-ratio: 2/3;">
                            <span style="font-size: 5rem;">📖</span>
                        </div>
                    @endif
                </div>

                <div class="col-md-8">
                    <h1 class="h3 fw-bold mb-2">{{ $book->title }}</h1>

                    @if($book->authors->isNotEmpty())
                        <p class="text-secondary mb-3">
                            by {{ $book->authors->pluck('name')->join(', ') }}
                        </p>
                    @endif

                    @if($book->category)
                        <span class="badge bg-primary-subtle text-primary-emphasis mb-3">
                            {{ $book->category->name }}
                        </span>
                    @endif

                    @if($book->description)
                        <p class="mb-4">{{ $book->description }}</p>
                    @endif

                    <div class="small text-secondary mb-4">
                        @if($book->published_year) Published: {{ $book->published_year }} @endif
                        @if($book->pages) · {{ $book->pages }} pages @endif
                        @if($book->isbn) · ISBN: {{ $book->isbn }} @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>