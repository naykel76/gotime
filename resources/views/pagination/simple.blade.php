{{-- simple pagination fith next and previous --}}

@if($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex space-between">

        <div>
            @if($paginator->onFirstPage())
                {{-- displays when on first page --}}
                <span class="btn disabled">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                {{-- displays when not on first page --}}
                <a href="{{ $paginator->previousPageUrl() }}" class="btn primary outline">
                    {!! __('pagination.previous') !!}
                </a>
            @endif
        </div>

        <div>
            @if($paginator->hasMorePages())
                {{-- displays when on last page --}}
                <a href="{{ $paginator->nextPageUrl() }}" class="btn primary outline">
                    {!! __('pagination.next') !!}
                </a>
            @else
                {{-- displays when on last page --}}
                <span class="btn disabled">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>
        
    </nav>
@endif