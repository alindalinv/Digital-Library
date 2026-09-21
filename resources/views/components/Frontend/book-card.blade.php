@props(['book', 'featured' => false])

<div class="card h-100 border-0 shadow-sm position-relative">

    {{-- Featured badge --}}
    @if($featured)
        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2 d-inline-flex align-items-center gap-1 z-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
            </svg>
            Featured
        </span>
    @endif

    {{-- Cover --}}
    <a href="{{ route('books.show', $book->slug) }}"
       class="text-decoration-none overflow-hidden rounded-top">

        @if (!empty($book->cover_image))
            <img src="{{ asset('storage/' . $book->cover_image) }}"
                 alt="{{ $book->title }}"
                 loading="lazy"
                 class="card-img-top"
                 style="height: 260px; object-fit: cover;"
                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'bg-secondary-subtle d-flex align-items-center justify-content-center\' style=\'height:260px;\'><span style=\'font-size:4rem;\' aria-hidden=\'true\'>📖</span></div>';">
        @else
            <div class="bg-secondary-subtle d-flex align-items-center justify-content-center"
                 style="height: 260px;">
                <span style="font-size: 4rem;" aria-hidden="true">📖</span>
            </div>
        @endif
    </a>

    <div class="card-body d-flex flex-column position-relative">

        {{-- Category --}}
        @if($book->category)
            <span class="badge bg-primary-subtle text-primary-emphasis align-self-start mb-2">
                {{ $book->category->name }}
            </span>
        @endif

        {{-- Title --}}
        <h5 class="card-title fw-semibold mb-1">
            <a href="{{ route('books.show', $book->slug) }}"
               class="text-dark text-decoration-none stretched-link">
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
                {{ Str::limit(strip_tags($book->description), 70, '…') }}
            </p>
        @endif

        {{-- Footer --}}
        <div class="mt-auto d-flex justify-content-between align-items-center">
            <div class="small text-secondary">
                @if($book->published_year)
                    {{ $book->published_year }}
                @endif

                @if($book->published_year && $book->pages)
                    <span class="mx-1">·</span>
                @endif

                @if($book->pages)
                    {{ number_format($book->pages) }} pages
                @endif
            </div>

            @if($book->stock > 0)
                <span class="badge bg-success-subtle text-success-emphasis">Available</span>
            @else
                <span class="badge bg-danger-subtle text-danger-emphasis">Out of stock</span>
            @endif
        </div>
    </div>
</div>