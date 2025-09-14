@php
    if (!isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet = $scrollTo !== false ? "(\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()" : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <!-- Mobile Pagination -->
        <div class="hidden on-sm:flex space-between items-center">
            @if ($paginator->onFirstPage())
                <button type="button" class="btn xs" disabled> Previous </button>
            @else
                <button wire:click="previousPage('{{ $paginator->getPageName() }}')"
                    x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                    type="button" class="btn xs"> Previous </button>
            @endif
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage('{{ $paginator->getPageName() }}')"
                    x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                    type="button" class="btn xs"> Next </button>
            @else
                <button type="button" class="btn xs" disabled> Next </button>
            @endif
        </div>

        <!-- Desktop Pagination -->
        <div class="flex space-between items-center on-sm:hidden">
            
            <!-- Status Text Showing X to Y of Z results -->
            <p class="text-sm">
                Showing <span class="fw6">{{ $paginator->firstItem() }}</span>
                to <span class="fw6">{{ $paginator->lastItem() }} </span>
                of <span class="fw6">{{ $paginator->total() }} </span> results
            </p>

            <nav role="navigation" aria-label="Pagination Navigation">
                {{-- Previous Icon --}}
                <div class="inline-flex">
                    @if ($paginator->onFirstPage())
                        <button type="button" class="btn px-1 py-05" disabled aria-label="Previous page">
                            <svg class="icon txt-muted" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @else
                        <button wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            type="button" class="btn px-1 py-05" aria-label="Previous page">
                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endif
                </div>

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true">
                            <button type="button" class="btn px-1 py-05" disabled>{{ $element }}</button>
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                @if ($page == $paginator->currentPage())
                                    <button type="button" class="btn px-1 py-05" disabled>{{ $page }}</button>
                                @else
                                    <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                        x-on:click="{{ $scrollIntoViewJsSnippet }}" aria-label="Go to page {{ $page }}"
                                        type="button" class="btn px-1 py-05"> {{ $page }} </button>
                                @endif
                            </span>
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Icon --}}
                <div class="inline-flex">
                    @if ($paginator->hasMorePages())
                        <button wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            type="button" class="btn px-1 py-05" aria-label="Next page">
                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @else
                        <button type="button" class="btn px-1 py-05" disabled aria-label="Next page">
                            <svg class="icon txt-muted" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    @endif
                </div>
            </nav>
        </div>
    @endif
</div>
