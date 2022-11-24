<div>
    @if($paginator->hasPages())
        @php(isset($this->numberOfPaginatorsRendered[$paginator->getPageName()]) ? $this->numberOfPaginatorsRendered[$paginator->getPageName()]++ : $this->numberOfPaginatorsRendered[$paginator->getPageName()] = 1)

            <nav role="navigation" aria-label="Pagination Navigation" class="flex va-t ha-c mt">

                {{-- previous page link (disabled when first) --}}
                <div id="pagination-left">

                    @if($paginator->onFirstPage())
                        <span class="btn disabled mr-05">
                            {!! __('pagination.previous') !!}
                        </span>
                    @else
                        <button wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="btn mr-05">
                            {!! __('pagination.previous') !!}
                        </button>
                    @endif

                </div>

                <div id="pagination-middle">

                    {{-- Pagination Elements --}}
                    @foreach($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if(is_string($element))
                            <span class="btn disabled" aria-disabled="true">{{ $element }}</span>
                        @endif
                        {{-- Array Of Links --}}
                        @if(is_array($element))
                            @foreach($element as $page => $url)
                                <span wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page{{ $page }}">
                                    @if($page == $paginator->currentPage())
                                        <span class="btn primary" aria-current="page">{{ $page }}</span>
                                    @else
                                        <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" class="btn" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                            {{ $page }}
                                        </button>
                                    @endif
                                </span>
                            @endforeach
                        @endif
                    @endforeach

                </div>

                {{-- next page link (disabled when last) --}}
                <div id="pagination-right">

                    @if($paginator->hasMorePages())
                        <button wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="btn ml-05">
                            {!! __('pagination.next') !!}
                        </button>
                    @else
                        <span class="btn disabled ml-05">
                            {!! __('pagination.next') !!}
                        </span>
                    @endif

                </div>

            </nav>

            {{-- results information (text) --}}
            <div class="tac mt-025">
                <span>{!! __('Showing') !!}</span>
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                <span>{!! __('to') !!}</span>
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                <span>{!! __('of') !!}</span>
                <span class="font-medium">{{ $paginator->total() }}</span>
                <span>{!! __('results') !!}</span>
            </div>

        @endif
</div>
