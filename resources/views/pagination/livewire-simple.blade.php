@php
    if (!isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet = $scrollTo !== false ? "(\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()" : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between items-center">
            <!-- Status Text Showing X to Y of Z results -->
            <p class="text-sm">
                Showing <span class="fw6">{{ $paginator->firstItem() }}</span>
                to <span class="fw6">{{ $paginator->lastItem() }} </span>
                of <span class="fw6">{{ $paginator->total() }} </span> results
            </p>
            <!-- Next and Previous Buttons -->
            <div>
                <!-- Previous Page -->
                @if ($paginator->onFirstPage())
                    <button type="button" class="btn sm" disabled> Previous </button>
                @else
                    @if (method_exists($paginator, 'getCursorName'))
                        <button wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->previousCursor()->encode() }}"
                            wire:click="setPage('{{ $paginator->previousCursor()->encode() }}','{{ $paginator->getCursorName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                            type="button" class="btn sm"> Previous </button>
                    @else
                        <button wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                            type="button" class="btn sm"> Previous </button>
                    @endif
                @endif
                <!-- Next Page -->
                @if ($paginator->hasMorePages())
                    @if (method_exists($paginator, 'getCursorName'))
                        <button wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->nextCursor()->encode() }}"
                            wire:click="setPage('{{ $paginator->nextCursor()->encode() }}','{{ $paginator->getCursorName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                            type="button" class="btn sm"> Next </button>
                    @else
                        <button wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                            type="button" class="btn sm"> Next </button>
                    @endif
                @else
                    <button type="button" class="btn sm" disabled> Next </button>
                @endif
            </div>
        </nav>
    @endif
</div>
