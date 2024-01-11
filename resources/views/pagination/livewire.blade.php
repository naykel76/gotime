@if($paginator->hasPages())
    <div class="flex space-between items-center mt">
        <div class="fw6 txt-sm">
            Results: {{ \Illuminate\Support\Number::format($paginator->total()) }}
        </div>

        <nav role="navigation" aria-label="Pagination Navigation" class="flex gap-1">
            <span>
                {{-- Previous Page Link --}}
                @if($paginator->onFirstPage())
                    <button type="button" class="btn rounded-05 xs" disabled>
                        Prev
                    </button>
                @else
                    @if(method_exists($paginator,'getCursorName'))
                        <button type="button" dusk="previousPage" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->previousCursor()->encode() }}" wire:click="setPage('{{ $paginator->previousCursor()->encode() }}','{{ $paginator->getCursorName() }}')" wire:loading.attr="disabled" class="btn rounded-05 xs">
                            Prev
                        </button>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="btn rounded-05 xs">
                            Prev
                        </button>
                    @endif
                @endif
            </span>

            <span>
                {{-- Next Page Link --}}
                @if($paginator->hasMorePages())
                    @if(method_exists($paginator,'getCursorName'))
                        <button type="button" dusk="nextPage" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->nextCursor()->encode() }}" wire:click="setPage('{{ $paginator->nextCursor()->encode() }}','{{ $paginator->getCursorName() }}')" wire:loading.attr="disabled" class="btn rounded-05 xs">
                            Next
                        </button>
                    @else
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" class="btn rounded-05 xs">
                            Next
                        </button>
                    @endif
                @else
                    <button type="button" class="btn rounded-05 xs" disabled>
                        Next
                    </button>
                @endif
            </span>
        </nav>
    </div>
@endif
