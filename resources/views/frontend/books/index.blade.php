<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 fw-semibold text-dark">{{ __('All Books') }}</h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="row g-4">
                @forelse($books as $book)
                    <div class="col-12 col-sm-6 col-lg-3">
                        <x-frontend.book-card :book="$book" />
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-secondary">No books available yet.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $books->links() }}
            </div>
        </div>
    </div>
</x-app-layout>