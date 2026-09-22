@if ($paginator->hasPages())
    <nav aria-label="Books pagination">
        <ul class="pagination justify-content-center align-items-center gap-1 mb-0">
            {{-- Previous page --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link rounded-2">Previous</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link rounded-2" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page">Previous</a>
                </li>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link border-0 bg-transparent">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link rounded-2">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link rounded-2" href="{{ $url }}" aria-label="Go to page {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next page --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link rounded-2" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">Next</a></li>
            @else
                <li class="page-item disabled" aria-disabled="true"><span class="page-link rounded-2">Next</span></li>
            @endif
        </ul>
    </nav>
@endif