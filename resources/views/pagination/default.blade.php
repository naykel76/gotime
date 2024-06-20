@if($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex ha-c mt">

        <div>
            @if($paginator->onFirstPage())
                {{-- displays when on first page --}}
                <span class="btn disabled mr-05">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                {{-- displays when not on first page --}}
                <a href="{{ $paginator->previousPageUrl() }}" class="btn mr-05">
                    {!! __('pagination.previous') !!}
                </a>
            @endif
        </div>

        {{-- Pagination Elements --}}
        @foreach($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if(is_string($element))
                <span class="btn disabled">{{ $element }}</span>
            @endif

            {{-- Array Of page Links --}}
            @if(is_array($element))
                @foreach($element as $page => $url)
                    @if($page == $paginator->currentPage())
                        <span class="btn primary mx-025">{{ $page }}</span>
                    @else
                        <a class="btn mx-025" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        <div>
            @if($paginator->hasMorePages())
                {{-- displays when on last page --}}
                <a href="{{ $paginator->nextPageUrl() }}" class="btn ml-05">
                    {!! __('pagination.next') !!}
                </a>
            @else
                {{-- displays when on last page --}}
                <span class="btn disabled ml-05">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

    </nav>
@endif
