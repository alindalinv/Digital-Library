<x-app-layout :title="$metaTitle ?? $book->title" :description="$metaDesc ?? null">
    @push('styles')
        <style>
            /* =========================================================
                   Book Detail - Responsive
                   ========================================================= */

            .book-detail {
                --book-radius: .75rem;
            }

            /* Cover */
            .book-detail-cover {
                width: 100%;
                max-width: 320px;
                margin-inline: auto;
                overflow: hidden;
                border-radius: var(--book-radius);
                background: #edf2f7;
                box-shadow: 0 .125rem .5rem rgba(0, 0, 0, .08);
            }

            .book-detail-cover img {
                width: 100%;
                height: auto;
                aspect-ratio: 2 / 3;
                object-fit: cover;
                display: block;
            }

            .book-detail-cover-placeholder {
                width: 100%;
                aspect-ratio: 2 / 3;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #edf2f7;
            }

            /* Details */
            .book-detail-title {
                font-size: clamp(1.5rem, 3vw, 2rem);
                line-height: 1.25;
                overflow-wrap: anywhere;
            }

            .book-detail-author {
                line-height: 1.7;
            }

            .book-detail-description {
                line-height: 1.75;
                overflow-wrap: anywhere;
            }

            /* Metadata */
            .book-meta-item {
                min-width: 0;
            }

            .book-meta-value {
                overflow-wrap: anywhere;
            }

            /* Actions */
            .book-actions {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: .75rem;
            }

            .book-actions .btn {
                min-height: 44px;
            }

            /* Attachments */
            .book-file-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
            }

            .book-file-info {
                min-width: 0;
                flex: 1 1 auto;
            }

            .book-file-name {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .book-file-actions {
                display: flex;
                flex-wrap: wrap;
                gap: .5rem;
                flex-shrink: 0;
            }

            /* Reviews */
            .book-review {
                height: 100%;
            }

            /* Related books */
            .related-books-grid>[class*="col-"] {
                display: flex;
            }

            .related-books-grid .catalog-book-card {
                width: 100%;
            }


            /* =========================================================
                   Tablet
                   ========================================================= */

            @media (max-width: 767.98px) {

                .book-detail {
                    padding-top: 1.5rem !important;
                    padding-bottom: 2rem !important;
                }

                .book-detail-cover {
                    max-width: 260px;
                }

                .book-detail-title {
                    margin-top: .25rem;
                }

                .book-actions {
                    align-items: stretch;
                }

                .book-actions .btn,
                .book-actions form {
                    flex: 1 1 auto;
                }

                .book-actions form .btn {
                    width: 100%;
                }

                .book-file-item {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .book-file-actions {
                    width: 100%;
                }

                .book-file-actions .btn {
                    flex: 1 1 auto;
                }
            }


            /* =========================================================
                   Mobile
                   ========================================================= */

            @media (max-width: 575.98px) {

                .book-detail {
                    padding-top: 1rem !important;
                }

                /* Breadcrumb */
                .book-detail-breadcrumb {
                    overflow-x: auto;
                    white-space: nowrap;
                    scrollbar-width: none;
                }

                .book-detail-breadcrumb::-webkit-scrollbar {
                    display: none;
                }

                /* Cover */
                .book-detail-cover {
                    max-width: 220px;
                }

                /* Title */
                .book-detail-title {
                    font-size: 1.45rem;
                    line-height: 1.3;
                }

                /* Description */
                .book-detail-description {
                    font-size: .95rem;
                    line-height: 1.7;
                }

                /* Metadata */
                .book-meta-item {
                    padding: .75rem;
                    border: 1px solid var(--bs-border-color);
                    border-radius: .625rem;
                    background: var(--bs-light);
                }

                /* Actions become full width */
                .book-actions {
                    display: grid;
                    grid-template-columns: 1fr;
                    gap: .625rem;
                }

                .book-actions>*,
                .book-actions form,
                .book-actions .btn {
                    width: 100%;
                }

                .book-actions .btn {
                    min-height: 46px;
                }

                /* Price */
                .book-price {
                    width: 100%;
                    margin-bottom: .25rem;
                }

                .book-price .h4 {
                    font-size: 1.35rem;
                }

                /* Attachments */
                .book-file-item {
                    padding: .875rem !important;
                    gap: .75rem;
                }

                .book-file-name {
                    white-space: normal;
                    overflow-wrap: anywhere;
                }

                .book-file-actions {
                    width: 100%;
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                }

                .book-file-actions .btn {
                    width: 100%;
                    justify-content: center;
                }

                /* When there is only one action */
                .book-file-actions .btn:only-child {
                    grid-column: 1 / -1;
                }

                /* Reviews */
                .book-review {
                    padding: 1rem !important;
                }

                /* Related books */
                .related-books-grid {
                    --bs-gutter-x: .75rem;
                    --bs-gutter-y: 1rem;
                }

                /* PDF viewer */
                #pdfModalTitle {
                    min-width: 0;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }

                #pdfModal>div>div:first-child {
                    padding: .5rem !important;
                    gap: .35rem !important;
                }

                #pdfModal button {
                    min-width: 36px;
                    min-height: 36px;
                    padding: 5px 8px !important;
                }

                #pdfWrap {
                    padding: .5rem 0 !important;
                }
            }


            /* =========================================================
                   Very Small Phones
                   ========================================================= */

            @media (max-width: 375px) {

                .book-detail-cover {
                    max-width: 190px;
                }

                .book-detail-title {
                    font-size: 1.3rem;
                }

                .book-file-actions {
                    grid-template-columns: 1fr;
                }

                .book-file-actions .btn {
                    width: 100%;
                }
            }
        </style>
    @endpush
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
                <div class="col-12 col-md-4 col-lg-3">

                    <div class="book-detail-cover">

                        @if ($book->cover_url)

                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }} cover" loading="lazy">

                        @else

                            <div class="book-detail-cover-placeholder">
                                <span style="font-size: 4rem;" aria-hidden="true">
                                    📖
                                </span>
                            </div>

                        @endif

                    </div>

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


                {{-- Details --}}
                <div class="col-12 col-md-8 col-lg-9">

                    <h1 class="book-detail-title fw-bold mb-2">
                        {{ $book->title }}
                    </h1>

                    @if ($book->authors->isNotEmpty())

                        <p class="book-detail-author text-secondary mb-3">
                            by

                            @foreach ($book->authors as $author)

                                <a href="{{ route('authors.show', $author) }}" class="fw-medium text-dark text-decoration-none">
                                    {{ $author->name }}
                                </a>@if (!$loop->last), @endif

                            @endforeach

                        </p>

                    @endif


                    {{-- Badges --}}
                    <div class="d-flex flex-wrap gap-2 mb-3">

                        @if ($book->category)

                            <a href="{{ route('books.index', ['category' => $book->category->id]) }}"
                                class="badge bg-primary-subtle text-primary-emphasis text-decoration-none">
                                {{ $book->category->name }}
                            </a>

                        @endif

                        @if ($book->is_featured)

                            <span class="badge bg-warning text-dark">
                                ⭐ Featured
                            </span>

                        @endif

                    </div>


                    {{-- Description --}}
                    @if ($book->description)

                        <div class="book-detail-description mb-4 text-body">
                            {!! $book->description !!}
                        </div>

                    @endif


                    {{-- Metadata --}}
                    <div class="row g-2 g-sm-3 mb-4">

                        @if ($book->published_year)

                            <div class="col-6 col-sm-3">
                                <div class="book-meta-item">
                                    <div class="small text-secondary">
                                        Year
                                    </div>

                                    <div class="book-meta-value fw-medium">
                                        {{ $book->published_year }}
                                    </div>
                                </div>
                            </div>

                        @endif


                        @if ($book->pages)

                            <div class="col-6 col-sm-3">
                                <div class="book-meta-item">
                                    <div class="small text-secondary">
                                        Pages
                                    </div>

                                    <div class="book-meta-value fw-medium">
                                        {{ number_format($book->pages) }}
                                    </div>
                                </div>
                            </div>

                        @endif


                        @if ($book->language)

                            <div class="col-6 col-sm-3">
                                <div class="book-meta-item">
                                    <div class="small text-secondary">
                                        Language
                                    </div>

                                    <div class="book-meta-value fw-medium">
                                        {{ $book->language }}
                                    </div>
                                </div>
                            </div>

                        @endif


                        @if ($book->isbn)

                            <div class="col-6 col-sm-3">
                                <div class="book-meta-item">
                                    <div class="small text-secondary">
                                        ISBN
                                    </div>

                                    <div class="book-meta-value fw-medium font-monospace">
                                        {{ $book->isbn }}
                                    </div>
                                </div>
                            </div>

                        @endif

                    </div>


                    {{-- Actions --}}
                    <div class="book-actions border-top pt-4">

                        @if ($book->price !== null)

                            <div class="book-price">
                                <span class="h4 fw-bold mb-0">
                                    ${{ number_format($book->price, 2) }}
                                </span>
                            </div>

                        @endif


                        @if ($book->stock > 0)

                            <button type="button" class="btn btn-primary btn-lg px-4" data-add-to-cart
                                data-book-id="{{ $book->id }}">
                                <i class="fas fa-shopping-cart me-1"></i>
                                Add to Cart
                            </button>


                            @auth

                                @if ($currentBorrowing)

                                    <span class="btn btn-outline-success btn-lg px-4 disabled" aria-disabled="true">
                                        {{ ucfirst($currentBorrowing->status) }} request
                                    </span>

                                @else

                                    <form method="POST" action="{{ route('borrowings.store', $book) }}">
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
                            <i class="fas fa-share-alt"></i>

                            <span class="d-sm-inline ms-1">
                                Share
                            </span>
                        </button>

                    </div>

                </div>

            </div>
            {{-- Attachments --}}
           @php
                $webUser = auth('web')->user();
                $adminUser = auth('admin')->user();

                if ($webUser) {
                    $user = $webUser;
                    $guard = 'web';
                } elseif ($adminUser) {
                    $user = $adminUser;
                    $guard = 'admin';
                } else {
                    $user = null;
                    $guard = null;
                }

                $isAuthenticated = $user !== null;

                $canPreview = $isAuthenticated
                    && $user->hasPermissionTo('ebook-files.view', $guard);

                $canDownload = $isAuthenticated
                    && $user->hasPermissionTo('ebook-files.download', $guard);
            @endphp

            @if ($book->files->isNotEmpty())
                <div class="mt-5">
                    <h2 class="h5 fw-semibold mb-3">
                        📎 Attachments
                        <span class="text-secondary fw-normal">
                            ({{ $book->files->count() }})
                        </span>
                    </h2>

                    <div class="list-group">

                        @foreach ($book->files as $file)

                            @php
                                $ext = strtoupper($file->file_type ?? 'FILE');

                                $size = $file->file_size
                                    ? number_format($file->file_size / 1048576, 2) . ' MB'
                                    : null;

                                $name = basename($file->file_path);

                                $isPdf = strtolower($file->file_type ?? '') === 'pdf';
                            @endphp

                            <div
                                class="list-group-item d-flex text-wrap justify-content-between align-items-center gap-3 flex-wrap">

                                {{-- File information --}}
                                <div class="d-flex align-items-center gap-3 min-w-0">

                                    <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis px-3 py-2">
                                        {{ $ext }}
                                    </span>

                                    <div class="w-100">
                                        <div class="fw-semibold" title="{{ $name }}">
                                            {{ $name }}
                                        </div>

                                        <div class="small text-secondary">

                                            @if ($file->is_primary)
                                                <span class="badge bg-primary-subtle text-primary-emphasis me-1">
                                                    Primary
                                                </span>
                                            @endif

                                            @if ($size)
                                                {{ $size }}
                                            @endif

                                        </div>
                                    </div>
                                </div>


                                {{-- Actions --}}
                                {{-- Actions --}}
                                <div class="d-flex gap-2 flex-shrink-0">

                                    {{-- Guest --}}
                                    @if (!$isAuthenticated)

                                        <a href="{{ route('login') }}"
                                            class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1">
                                            🔒 Login to access
                                        </a>

                                    @else

                                        {{-- Preview --}}
                                        @if ($isPdf && $canPreview)

                                            <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1"
                                                data-pdf-open data-pdf-url="{{ route('ebooks.stream', $file) }}"
                                                data-pdf-embed-url="{{ route('ebooks.embed', $file) }}" data-pdf-title="{{ $name }}"
                                                aria-label="Preview {{ $name }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                                                    viewBox="0 0 16 16" aria-hidden="true">
                                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                    <path
                                                        d="M0 8s3-5.5 8-5.5S16 8 16 8s-3-5.5-8-5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                                </svg>

                                                Preview
                                            </button>

                                        @endif

                                        {{-- Download --}}
                                        @if ($canDownload)

                                            <a href="{{ route('ebooks.download', $file) }}"
                                                class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1"
                                                aria-label="Download {{ $name }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                                                    viewBox="0 0 16 16" aria-hidden="true">
                                                    <path
                                                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.1a.5.5 0 0 1 1 0v2.1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.1a.5.5 0 0 0-.5-.5" />
                                                    <path
                                                        d="M7.646 11.854a.5.5 0 0 0 .708 0l-3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                                                </svg>

                                                Download
                                            </a>

                                        @endif

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
                <strong id="pdfModalTitle" style="flex:1; font-size:14px;">Preview</strong>
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

                    /*
                    * ---------------------------------------------------------
                    * Open PDF preview
                    * ---------------------------------------------------------
                    */
                    document.addEventListener('click', function (e) {
                        const btn = e.target.closest('[data-pdf-open]');

                        if (btn) {
                            e.preventDefault();

                            openPdf(
                                btn.dataset.pdfUrl,
                                btn.dataset.pdfTitle,
                                btn.dataset.pdfEmbedUrl || null
                            );

                            return;
                        }

                        /*
                        * Close modal when clicking close button or backdrop.
                        */
                        if (e.target === closeEl || e.target === modal) {
                            closePdf();
                        }
                    });

                    /*
                    * ---------------------------------------------------------
                    * Open PDF
                    * ---------------------------------------------------------
                    */
                    function openPdf(url, title, embedUrl) {
                        titleEl.textContent = title || 'Preview';

                        wrap.innerHTML = `
                                <div style="
                                    color:#eee;
                                    padding:40px;
                                    text-align:center;
                                ">
                                    Loading…
                                </div>
                            `;

                        modal.style.display = 'block';
                        document.body.style.overflow = 'hidden';

                        /*
                        * Add optional "Open full viewer" button.
                        *
                        * This uses the embed route:
                        * /ebooks/{ebookFile}/embed
                        */
                        addEmbedButton(embedUrl);

                        pdfjsLib
                            .getDocument(url)
                            .promise
                            .then(function (pdf) {
                                pdfDoc = pdf;
                                pageNum = 1;

                                pageCntEl.textContent = pdf.numPages;

                                render(pageNum);
                            })
                            .catch(function (err) {
                                wrap.innerHTML = `
                                        <div style="
                                            color:#fca5a5;
                                            padding:40px;
                                            text-align:center;
                                        ">
                                            Failed to load PDF:
                                            ${escapeHtml(err.message)}
                                        </div>
                                    `;
                            });
                    }

                    /*
                    * ---------------------------------------------------------
                    * Add optional full viewer button
                    * ---------------------------------------------------------
                    */
                    function addEmbedButton(embedUrl) {
                        /*
                        * Look for an existing button.
                        */
                        let embedBtn = document.getElementById('pdfOpenEmbed');

                        if (!embedBtn) {
                            embedBtn = document.createElement('a');

                            embedBtn.id = 'pdfOpenEmbed';
                            embedBtn.target = '_blank';
                            embedBtn.rel = 'noopener noreferrer';

                            embedBtn.className =
                                'btn btn-sm btn-outline-light d-inline-flex align-items-center gap-1';

                            embedBtn.innerHTML = `
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="14"
                                        height="14"
                                        fill="currentColor"
                                        viewBox="0 0 16 16"
                                        aria-hidden="true"
                                    >
                                        <path d="M6.354 5.5H3.5A1.5 1.5 0 0 0 2 7v6a1.5 1.5 0 0 0 1.5 1.5h6A1.5 1.5 0 0 0 11 13v-2.854a.5.5 0 0 0-1 0V13a.5.5 0 0 1-.5.5h-6A.5.5 0 0 1 3 13V7a.5.5 0 0 1 .5-.5h2.854a.5.5 0 0 0 0-1" />
                                        <path d="M13.5 2a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V3.707l-4.146 4.147a.5.5 0 0 1-.708-.708L12.293 3H10.5a.5.5 0 0 1 0-1z" />
                                    </svg>
                                    Open Full Viewer
                                `;

                            /*
                            * Put the button into the modal header/action area.
                            *
                            * Change this selector if your modal uses a different
                            * toolbar container.
                            */
                            const header = titleEl?.parentElement;

                            if (header) {
                                header.appendChild(embedBtn);
                            }
                        }

                        if (embedUrl) {
                            embedBtn.href = embedUrl;
                            embedBtn.style.display = 'inline-flex';
                        } else {
                            embedBtn.style.display = 'none';
                        }
                    }

                    /*
                    * ---------------------------------------------------------
                    * Close PDF
                    * ---------------------------------------------------------
                    */
                    function closePdf() {
                        modal.style.display = 'none';

                        wrap.innerHTML = `
                                <div style="
                                    color:#eee;
                                    padding:40px;
                                    text-align:center;
                                ">
                                    Loading…
                                </div>
                            `;

                        document.body.style.overflow = '';

                        pdfDoc = null;
                        pageNum = 1;
                        rendering = false;
                        pending = null;
                    }

                    /*
                    * ---------------------------------------------------------
                    * Render PDF page
                    * ---------------------------------------------------------
                    */
                    function render(num) {
                        if (!pdfDoc) {
                            return;
                        }

                        rendering = true;

                        pdfDoc.getPage(num).then(function (page) {
                            const viewport = page.getViewport({
                                scale: scale
                            });

                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');

                            canvas.width = viewport.width;
                            canvas.height = viewport.height;

                            canvas.style.boxShadow =
                                '0 2px 6px rgba(0,0,0,.5)';

                            canvas.style.background = '#fff';

                            wrap.innerHTML = '';
                            wrap.appendChild(canvas);

                            return page.render({
                                canvasContext: ctx,
                                viewport: viewport
                            }).promise;
                        }).then(function () {
                            rendering = false;

                            if (pending !== null) {
                                const nextPage = pending;

                                pending = null;

                                render(nextPage);
                            }
                        }).catch(function () {
                            rendering = false;
                        });

                        pageNumEl.textContent = num;

                        prevBtn.disabled = num <= 1;
                        nextBtn.disabled = num >= pdfDoc.numPages;
                    }

                    /*
                    * ---------------------------------------------------------
                    * Queue rendering
                    * ---------------------------------------------------------
                    */
                    function queueRender(num) {
                        if (!pdfDoc) {
                            return;
                        }

                        if (rendering) {
                            pending = num;
                            return;
                        }

                        render(num);
                    }

                    /*
                    * ---------------------------------------------------------
                    * Previous / Next
                    * ---------------------------------------------------------
                    */
                    prevBtn.addEventListener('click', function () {
                        if (pdfDoc && pageNum > 1) {
                            pageNum--;
                            queueRender(pageNum);
                        }
                    });

                    nextBtn.addEventListener('click', function () {
                        if (pdfDoc && pageNum < pdfDoc.numPages) {
                            pageNum++;
                            queueRender(pageNum);
                        }
                    });

                    /*
                    * ---------------------------------------------------------
                    * Zoom
                    * ---------------------------------------------------------
                    */
                    zoomIn.addEventListener('click', function () {
                        if (!pdfDoc) {
                            return;
                        }

                        scale *= 1.2;

                        queueRender(pageNum);
                    });

                    zoomOut.addEventListener('click', function () {
                        if (!pdfDoc) {
                            return;
                        }

                        scale = Math.max(0.3, scale / 1.2);

                        queueRender(pageNum);
                    });

                    /*
                    * ---------------------------------------------------------
                    * Fit to width
                    * ---------------------------------------------------------
                    */
                    fitBtn.addEventListener('click', function () {
                        if (!pdfDoc) {
                            return;
                        }

                        pdfDoc.getPage(pageNum).then(function (page) {
                            const base = page.getViewport({
                                scale: 1
                            });

                            scale = Math.max(
                                0.3,
                                (wrap.clientWidth - 32) / base.width
                            );

                            queueRender(pageNum);
                        });
                    });

                    /*
                    * ---------------------------------------------------------
                    * Keyboard shortcuts
                    * ---------------------------------------------------------
                    */
                    document.addEventListener('keydown', function (e) {
                        if (modal.style.display !== 'block') {
                            return;
                        }

                        if (e.key === 'Escape') {
                            closePdf();
                            return;
                        }

                        if (e.key === 'ArrowRight' || e.key === 'PageDown') {
                            nextBtn.click();
                        }

                        if (e.key === 'ArrowLeft' || e.key === 'PageUp') {
                            prevBtn.click();
                        }

                        if (e.key === '+' || e.key === '=') {
                            zoomIn.click();
                        }

                        if (e.key === '-') {
                            zoomOut.click();
                        }
                    });

                    /*
                    * ---------------------------------------------------------
                    * Basic HTML escaping for PDF.js error messages.
                    * ---------------------------------------------------------
                    */
                    function escapeHtml(value) {
                        const div = document.createElement('div');

                        div.textContent = value ?? '';

                        return div.innerHTML;
                    }
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