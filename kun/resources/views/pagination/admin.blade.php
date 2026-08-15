@if ($paginator->hasPages())
<nav class="admin-pagination" role="navigation" aria-label="Pagination">
    @if ($paginator->onFirstPage())
        <span class="page-btn disabled" aria-disabled="true">&lsaquo;</span>
    @else
        <a class="page-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lsaquo;</a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="page-btn disabled">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="page-btn active">{{ $page }}</span>
                @else
                    <a class="page-btn" href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a class="page-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">&rsaquo;</a>
    @else
        <span class="page-btn disabled" aria-disabled="true">&rsaquo;</span>
    @endif
</nav>
@endif
