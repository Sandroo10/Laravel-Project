<nav class="pagination-nav" aria-label="Vacancy pages">
    <p class="pagination-summary">Showing <strong>{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</strong> of <strong>{{ $paginator->total() }}</strong> roles</p>

    <div class="page-links">
        @if($paginator->onFirstPage())
            <span class="page-link page-direction" aria-disabled="true"><span aria-hidden="true">←</span><span class="page-direction-label">Previous</span><span class="sr-only">Previous page unavailable</span></span>
        @else
            <a class="page-link page-direction" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page"><span aria-hidden="true">←</span><span class="page-direction-label">Previous</span></a>
        @endif

        @foreach($elements as $element)
            @if(is_string($element))
                <span class="page-ellipsis" aria-hidden="true">{{ $element }}</span>
            @elseif(is_array($element))
                @foreach($element as $page => $url)
                    @if($page === $paginator->currentPage())
                        <span class="page-link current-page" aria-current="page" aria-label="Page {{ $page }}">{{ $page }}</span>
                    @else
                        <a class="page-link" href="{{ $url }}" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if($paginator->hasMorePages())
            <a class="page-link page-direction" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page"><span class="page-direction-label">Next</span><span aria-hidden="true">→</span></a>
        @else
            <span class="page-link page-direction" aria-disabled="true"><span class="page-direction-label">Next</span><span aria-hidden="true">→</span><span class="sr-only">Next page unavailable</span></span>
        @endif
    </div>
</nav>
