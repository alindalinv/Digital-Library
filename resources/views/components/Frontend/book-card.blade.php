@props(['book', 'featured' => false])

<div class="card h-100 border-0 shadow-sm position-relative">

    @if($featured)
        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">
            Featured
        </span>
    @endif

    {{-- Cover --}}
    <a href="{{ route('books.show', $book->slug) }}" class="text-decoration-none">
        @if($book->cover_image)
            <img src="{{ asset('storage/' . $book->cover_image) }}"
                 alt="{{ $book->title }}"
                 class="card-img-top"
                 style="height: 260px; object-fit: cover;">
        @else
            <div class="bg-secondary-subtle d-flex align-items-center justify-content-center"
                 style="height: 260px;">
                <span style="font-size: 4rem;">📖</span>
            </div>
        @endif
    </a>

    <div class="card-body d-flex flex-column">

        {{-- Category --}}
        @if($book->category)
            <span class="badge bg-primary-subtle text-primary-emphasis align-self-start mb-2">
                {{ $book->category->name }}
            </span>
        @endif

        {{-- Title --}}
        <h5 class="card-title fw-semibold mb-1">
            <a href="{{ route('books.show', $book->slug) }}"
               class="text-dark text-decoration-none">
                {{ $book->title }}
            </a>
        </h5>

        {{-- Authors --}}
        @if($book->authors->isNotEmpty())
            <p class="small text-secondary mb-2">
                by {{ $book->authors->pluck('name')->join(', ') }}
            </p>
        @endif

        {{-- Short description --}}
        @if($book->description)
            <p class="small text-secondary mb-3">
                {{ Str::limit($book->description, 70) }}
            </p>
        @endif

        {{-- Footer: meta + action --}}
        <div class="mt-auto d-flex justify-content-between align-items-center">
            <div class="small text-secondary">
                @if($book->published_year)
                    {{ $book->published_year }}
                @endif
                @if($book->published_year && $book->pages)
                    ·
                @endif
                @if($book->pages)
                    {{ $book->pages }} pages
                @endif
            </div>

            @if($book->stock > 0)
                <span class="badge bg-success-subtle text-success-emphasis">
                    Available
                </span>
            @else
                <span class="badge bg-danger-subtle text-danger-emphasis">
                    Out of stock
                </span>
            @endif
        </div>

    </div>
</div>