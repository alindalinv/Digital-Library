<x-app-layout :title="$metaTitle ?? $book->title" :description="$metaDesc ?? null">

    <x-slot name="header">
        <div class="container py-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('books.index') }}" class="text-decoration-none">Books</a>
                    </li>
                    @if ($book->category)
                        <li class="breadcrumb-item">
                            <a href="{{ route('books.index', ['category' => $book->category->id]) }}"
                                class="text-decoration-none">
                                {{ $book->category->name }}
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ Str::limit($book->title, 40) }}
                    </li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="container">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Main book details --}}
            <div class="row g-4 g-lg-5">

                {{-- Cover --}}
                <div class="col-md-4 col-lg-3">
                    <div class="sticky-top" style="top: 1rem;">
                        @if ($book->cover_url)
                            <div class="rounded-3 shadow-sm overflow-hidden">
                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }} cover" loading="lazy"
                                    class="object-fit-cover w-100 h-100">
                            </div>
                        @else
                            <div class="bg-secondary-subtle rounded-3 d-flex align-items-center justify-content-center"
                                style="aspect-ratio: 2/3;">
                                <span style="font-size: 5rem;" aria-hidden="true">📖</span>
                            </div>
                        @endif

                        <div class="mt-3">
                            @if ($book->stock > 0)
                                <span class="badge bg-success-subtle text-success-emphasis w-100 py-2">
                                    ✓ Available ({{ $book->stock }} in stock)
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger-emphasis w-100 py-2">
                                    Out of stock
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Details --}}
                <div class="col-md-8 col-lg-9">

                    <h1 class="h3 fw-bold mb-2">{{ $book->title }}</h1>

                    @if ($book->authors->isNotEmpty())
                        <p class="text-secondary mb-3">
                            by
                            @foreach ($book->authors as $author)
                                <a
                                    href="{{ route('authors.show', $author) }}"
                                    class="fw-medium text-dark text-decoration-none author-link"
                                >
                                    {{ $author->name }}
                                </a>@if (!$loop->last), @endif
                            @endforeach
                        </p>
                    @endif

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if ($book->category)
                            <a href="{{ route('books.index', ['category' => $book->category->id]) }}"
                                class="badge bg-primary-subtle text-primary-emphasis text-decoration-none">
                                {{ $book->category->name }}
                            </a>
                        @endif
                        @if ($book->is_featured)
                            <span class="badge bg-warning text-dark">⭐ Featured</span>
                        @endif
                    </div>

                    @if ($book->description)
                        <div class="prose mb-4 text-body">
                            {!! $book->description !!}
                        </div>
                    @endif

                    <div class="row g-3 mb-4">
                        @if ($book->published_year)
                            <div class="col-6 col-sm-3">
                                <div class="small text-secondary">Year</div>
                                <div class="fw-medium">{{ $book->published_year }}</div>
                            </div>
                        @endif
                        @if ($book->pages)
                            <div class="col-6 col-sm-3">
                                <div class="small text-secondary">Pages</div>
                                <div class="fw-medium">{{ number_format($book->pages) }}</div>
                            </div>
                        @endif
                        @if ($book->language)
                            <div class="col-6 col-sm-3">
                                <div class="small text-secondary">Language</div>
                                <div class="fw-medium">{{ $book->language }}</div>
                            </div>
                        @endif
                        @if ($book->isbn)
                            <div class="col-6 col-sm-3">
                                <div class="small text-secondary">ISBN</div>
                                <div class="fw-medium font-monospace">{{ $book->isbn }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-3 border-top pt-4">

                        @if ($book->price !== null)
                            <div class="me-2">
                                <span class="h4 fw-bold mb-0">${{ number_format($book->price, 2) }}</span>
                            </div>
                        @endif

                        @if ($book->stock > 0)
                            <button type="button" class="btn btn-primary btn-lg px-4" data-add-to-cart
                                data-book-id="{{ $book->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    viewBox="0 0 16 16" class="me-1" aria-hidden="true">
                                    <path
                                        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" />
                                </svg>
                                Add to Cart
                            </button>

                            @auth
                                @if ($currentBorrowing)
                                    <span class="btn btn-outline-success btn-lg px-4 disabled" aria-disabled="true">
                                        {{ ucfirst($currentBorrowing->status) }} request
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('borrowings.store', $book) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-lg px-4">
                                            Borrow Book
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg px-4">
                                    Borrow Book
                                </a>
                            @endauth
                        @else
                            <button type="button" class="btn btn-secondary btn-lg px-4" disabled>
                                Out of Stock
                            </button>
                        @endif

                        <button type="button" class="btn btn-outline-secondary" data-share
                            data-share-url="{{ url()->current() }}" data-share-title="{{ $book->title }}"
                            aria-label="Share this book">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                viewBox="0 0 16 16" aria-hidden="true">
                                <path
                                    d="M11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Attachments --}}
            @php
                $canPreview = auth()->check() && auth()->user()->can('ebook-files.view');
                $canDownload = auth()->check() && auth()->user()->can('ebook-files.download');
            @endphp

            @if ($book->files->isNotEmpty())
                <div class="mt-5">
                    <h2 class="h5 fw-semibold mb-3">
                        📎 Attachments
                        <span class="text-secondary fw-normal">({{ $book->files->count() }})</span>
                    </h2>

                    <div class="list-group">
                        @foreach ($book->files as $file)
                            @php
                                $ext = strtoupper($file->file_type ?? 'FILE');
                                $size = $file->file_size
                                    ? number_format($file->file_size / 1048576, 2) . ' MB'
                                    : null;
                                $name = basename($file->file_path);
                                $isPdf = strtolower($file->file_type) === 'pdf';
                            @endphp

                            <div class="list-group-item d-flex justify-content-between align-items-center gap-3 flex-wrap">
                                <div class="d-flex align-items-center gap-3 min-w-0">
                                    <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis px-3 py-2">
                                        {{ $ext }}
                                    </span>
                                    <div class="min-w-0">
                                        <div class="fw-semibold text-truncate" style="max-width: 32ch;" title="{{ $name }}">
                                            {{ $name }}
                                        </div>
                                        <div class="small text-secondary">
                                            @if ($file->is_primary)
                                                <span class="badge bg-primary-subtle text-primary-emphasis me-1">Primary</span>
                                            @endif
                                            @if ($size){{ $size }}@endif
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 flex-shrink-0">
                                    @if (!auth()->check())
                                        <a href="{{ route('login') }}"
                                            class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1">
                                            🔒 Login to access
                                        </a>
                                    @else
                                        @if ($isPdf && $canPreview)
                                            <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1"
                                                data-pdf-open data-pdf-url="{{ route('ebooks.stream', $file) }}"
                                                data-pdf-title="{{ $name }}" aria-label="Preview {{ $name }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                                                    viewBox="0 0 16 16" aria-hidden="true">
                                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                    <path
                                                        d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                                </svg>
                                                Preview
                                            </button>
                                        @endif

                                        @if ($canDownload)
                                            <a href="{{ route('ebooks.download', $file) }}"
                                                class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1"
                                                aria-label="Download {{ $name }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                                                    viewBox="0 0 16 16" aria-hidden="true">
                                                    <path
                                                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.1a.5.5 0 0 1 1 0v2.1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.1a.5.5 0 0 1 .5-.5" />
                                                    <path
                                                        d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                                                </svg>
                                                Download
                                            </a>
                                        @endif

                                        @unless ($canPreview || $canDownload)
                                            <span class="badge bg-secondary-subtle text-secondary-emphasis px-3 py-2">
                                                🔒 No access
                                            </span>
                                        @endunless
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Reviews --}}
            @if ($book->reviews->isNotEmpty())
                <div class="mt-5">
                    <h2 class="h5 fw-semibold mb-3">
                        Reviews
                        <span class="text-secondary fw-normal">({{ $book->reviews->count() }})</span>
                    </h2>

                    <div class="row g-3">
                        @foreach ($book->reviews as $review)
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        @if ($review->user?->avatar)
                                            <img src="{{ $review->user->avatar }}" alt="{{ $review->user->name }}"
                                                class="rounded-circle" width="32" height="32">
                                        @else
                                            <div class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center"
                                                style="width:32px;height:32px;">
                                                <span class="small fw-bold text-secondary">
                                                    {{ Str::substr($review->user?->name ?? '?', 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-medium small">{{ $review->user?->name ?? 'Anonymous' }}</div>
                                            <div class="text-secondary" style="font-size:.75rem;">
                                                {{ $review->created_at?->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 small">{{ $review->body }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Related books --}}
            @if ($relatedBooks->isNotEmpty())
                <div class="mt-5">
                    <h2 class="h5 fw-semibold mb-3">You might also like</h2>

                    <style>
                        .related-books-grid>[class*="col-"] {
                            display: flex;
                        }

                        .related-books-grid .catalog-book-card {
                            width: 100%;
                        }
                    </style>

                    <div class="row g-3 g-md-4 related-books-grid">
                        @foreach ($relatedBooks as $related)
                            <div class="col-6 col-md-4 col-lg-3 d-flex">
                                <x-frontend.book-card :book="$related" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- =========================================================
    PDF preview overlay (no Bootstrap dependency)
    ========================================================== --}}
    {{-- Bootstrap PDF modal --}}
    {{-- PDF modal --}}
    <div id="pdfModal" style="display:none; position:fixed; inset:0; z-index:1055; background:rgba(0,0,0,.85);">
        <div style="position:absolute; inset:0; display:flex; flex-direction:column;">

            {{-- Toolbar --}}
            <div style="background:#111827; color:#fff; padding:10px 16px; display:flex; align-items:center; gap:8px;">
                <strong id="pdfModalTitle" style="flex:1; font-size:14px;">Preview</strong>
                <button type="button" id="pdfPrev"
                    style="background:#374151; color:#fff; border:0; padding:6px 12px; border-radius:4px; cursor:pointer;">‹</button>
                <span style="font-size:13px; padding:0 8px;">Page <span id="pdfNum">1</span> / <span
                        id="pdfCount">?</span></span>
                <button type="button" id="pdfNext"
                    style="background:#374151; color:#fff; border:0; padding:6px 12px; border-radius:4px; cursor:pointer;">›</button>
                <button type="button" id="pdfZoomOut"
                    style="background:#374151; color:#fff; border:0; padding:6px 12px; border-radius:4px; cursor:pointer;">−</button>
                <button type="button" id="pdfZoomIn"
                    style="background:#374151; color:#fff; border:0; padding:6px 12px; border-radius:4px; cursor:pointer;">+</button>
                <button type="button" id="pdfFit"
                    style="background:#374151; color:#fff; border:0; padding:6px 12px; border-radius:4px; cursor:pointer;">Fit</button>
                <button type="button" id="pdfModalClose"
                    style="background:#dc2626; color:#fff; border:0; padding:6px 12px; border-radius:4px; cursor:pointer; margin-left:8px;">Close</button>
            </div>

            {{-- Canvas --}}
            <div id="pdfWrap"
                style="flex:1; overflow:auto; text-align:center; padding:16px 0; background:#525659; position:relative;">
                <div id="pdfLoading" style="color:#eee; padding:40px;">Loading…</div>
            </div>
        </div>
    </div>
    @push('scripts')
            <script>
                (function () {
                    const modal = document.getElementById('pdfModal');
                    const wrap = document.getElementById('pdfWrap');
                    const loading = document.getElementById('pdfLoading');
                    const titleEl = document.getElementById('pdfModalTitle');
                    const pageNumEl = document.getElementById('pdfNum');
                    const pageCntEl = document.getElementById('pdfCount');
                    const prevBtn = document.getElementById('pdfPrev');
                    const nextBtn = document.getElementById('pdfNext');
                    const zoomIn = document.getElementById('pdfZoomIn');
                    const zoomOut = document.getElementById('pdfZoomOut');
                    const fitBtn = document.getElementById('pdfFit');
                    const closeEl = document.getElementById('pdfModalClose');

                    let pdfDoc = null;
                    let pageNum = 1;
                    let scale = 1.4;
                    let rendering = false;
                    let pending = null;

                    // Delegate open from any [data-pdf-open]
                    document.addEventListener('click', function (e) {
                        const btn = e.target.closest('[data-pdf-open]');
                        if (btn) {
                            e.preventDefault();
                            open(btn.dataset.pdfUrl, btn.dataset.pdfTitle);
                        }
                        if (e.target === closeEl) close();
                        if (e.target === modal) close();
                    });

                    function open(url, title) {
                        titleEl.textContent = title || 'Preview';
                        wrap.innerHTML = '<div id="pdfLoading" style="color:#eee;padding:40px;text-align:center;">Loading…</div>';
                        modal.style.display = 'block';
                        document.body.style.overflow = 'hidden';

                        pdfjsLib.getDocument(url).promise.then(function (pdf) {
                            pdfDoc = pdf;
                            pageNum = 1;
                            pageCntEl.textContent = pdf.numPages;
                            render(pageNum);
                        }).catch(function (err) {
                            wrap.innerHTML = '<div style="color:#fca5a5;padding:40px;text-align:center;">Failed to load PDF: ' + err.message + '</div>';
                        });
                    }

                    function close() {
                        modal.style.display = 'none';
                        wrap.innerHTML = '<div id="pdfLoading" style="color:#eee;padding:40px;text-align:center;">Loading…</div>';
                        document.body.style.overflow = '';
                        pdfDoc = null;
                    }

                    function render(num) {
                        rendering = true;
                        pdfDoc.getPage(num).then(function (page) {
                            const viewport = page.getViewport({ scale: scale });
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');
                            canvas.width = viewport.width;
                            canvas.height = viewport.height;
                            canvas.style.boxShadow = '0 2px 6px rgba(0,0,0,.5)';
                            canvas.style.background = '#fff';

                            wrap.innerHTML = '';
                            wrap.appendChild(canvas);

                            page.render({ canvasContext: ctx, viewport: viewport }).promise.then(function () {
                                rendering = false;
                                if (pending !== null) { const p = pending; pending = null; render(p); }
                            });
                        });
                        pageNumEl.textContent = num;
                        prevBtn.disabled = num <= 1;
                        nextBtn.disabled = num >= pdfDoc.numPages;
                    }

                    function queueRender(num) {
                        if (rendering) { pending = num; return; }
                        render(num);
                    }

                    prevBtn.addEventListener('click', function () { if (pageNum > 1) queueRender(--pageNum); });
                    nextBtn.addEventListener('click', function () { if (pageNum < pdfDoc.numPages) queueRender(++pageNum); });
                    zoomIn.addEventListener('click', function () { scale *= 1.2; queueRender(pageNum); });
                    zoomOut.addEventListener('click', function () { scale = Math.max(0.3, scale / 1.2); queueRender(pageNum); });
                    fitBtn.addEventListener('click', function () {
                        pdfDoc.getPage(pageNum).then(function (page) {
                            const base = page.getViewport({ scale: 1 });
                            scale = (wrap.clientWidth - 32) / base.width;
                            queueRender(pageNum);
                        });
                    });

                    document.addEventListener('keydown', function (e) {
                        if (modal.style.display !== 'block') return;
                        if (e.key === 'Escape') close();
                        if (e.key === 'ArrowRight' || e.key === 'PageDown') nextBtn.click();
                        if (e.key === 'ArrowLeft' || e.key === 'PageUp') prevBtn.click();
                        if (e.key === '+' || e.key === '=') zoomIn.click();
                        if (e.key === '-') zoomOut.click();
                    });
                })();
            </script>
            {{-- Structured data (JSON-LD) --}}
            <script type="application/ld+json">
                {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Book',
            'name' => $book->title,
            'isbn' => $book->isbn,
            'numberOfPages' => $book->pages,
            'inLanguage' => $book->language,
            'datePublished' => $book->published_year,
            'description' => $book->description ? Str::limit(strip_tags($book->description), 160) : null,
            'image' => $book->cover_url,
            'author' => $book->authors->map(fn($a) => ['@type' => 'Person', 'name' => $a->name])->values()->all(),
            'publisher' => $book->publisher
                ? ['@type' => 'Organization', 'name' => $book->publisher->name]
                : null,
            'offers' => $book->price !== null
                ? [
                    '@type' => 'Offer',
                    'price' => $book->price,
                    'priceCurrency' => 'USD',
                    'availability' => $book->stock > 0
                        ? 'https://schema.org/InStock'
                        : 'https://schema.org/OutOfStock',
                ]
                : null,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
            </script>
    @endpush
</x-app-layout>