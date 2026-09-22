<div class="row g-4">
    @forelse($books as $book)
        <div class="col-12 col-sm-6 col-lg-3 catalog-card">
            <x-frontend.book-card :book="$book" />
        </div>
    @empty
        <div class="col-12">
            <div class="rounded-3 border bg-white px-3 py-5 text-center">
                <div class="display-6 mb-2" aria-hidden="true">⌕</div>
                <h3 class="h5 text-dark">No books found</h3>
                <p class="text-secondary mb-3">Try a different search, category, or sort option.</p>
                <a href="{{ route('books.index') }}" class="btn btn-outline-primary">Reset filters</a>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $books->onEachSide(1)->links('frontend.pagination') }}</div>