@if($paginator->hasPages())
    <div class="flex space-between my">
        <div class="fw6 txt-sm">
            Results: {{ \Illuminate\Support\Number::format($paginator->total()) }}
        </div>
        <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">
            <div class="flex space-between w-full gap">
                @if($paginator->onFirstPage())
                    <span class="btn rounded-05 xs disabled"> Previous </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="btn rounded-05 xs">
                        Previous
                    </a>
                @endif

                @if($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="btn rounded-05 xs"> Next </a>
                @else
                    <span class="btn rounded-05 xs disabled"> Next </span>
                @endif
            </div>
        </nav>
    </div>
@endif
