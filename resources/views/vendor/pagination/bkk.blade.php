@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="ui-pagination">
    <div class="ui-pagination-info">
        Menampilkan
        <span class="font-semibold">{{ $paginator->firstItem() ?? 0 }}</span>–<span class="font-semibold">{{ $paginator->lastItem() ?? 0 }}</span>
        dari <span class="font-semibold">{{ $paginator->total() }}</span>
    </div>

    <div class="ui-pagination-links">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
        <span class="ui-pagination-btn ui-pagination-btn-disabled" aria-disabled="true">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="ui-pagination-btn" aria-label="Halaman sebelumnya">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
            <span class="ui-pagination-ellipsis">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                    <span class="ui-pagination-btn ui-pagination-btn-active" aria-current="page">{{ $page }}</span>
                    @else
                    <a href="{{ $url }}" class="ui-pagination-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="ui-pagination-btn" aria-label="Halaman berikutnya">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @else
        <span class="ui-pagination-btn ui-pagination-btn-disabled" aria-disabled="true">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </span>
        @endif
    </div>
</nav>
@endif
