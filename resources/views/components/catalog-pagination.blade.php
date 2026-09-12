@props(['paginator', 'label'])

@if ($paginator->hasPages())
    <nav class="catalog-pagination" aria-label="{{ $label }}">
        @if ($paginator->onFirstPage())<span>← Previous</span>@else<a href="{{ $paginator->previousPageUrl() }}">← Previous</a>@endif
        <span>Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>
        @if ($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}">Next →</a>@else<span>Next →</span>@endif
    </nav>
@endif
