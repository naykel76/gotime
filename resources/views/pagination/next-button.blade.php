@if ($paginator->hasMorePages())
    <button wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}"
        wire:loading.attr="disabled" type="button" class="gap-05 flex-centered min-wh-2.5 py-05 px-075 ends:rounded-lg gray state-gray bdr bdr-gray-200" aria-label="Go to next page">
        <span>Next</span>
        <x-gt-icon name="chevron-right" class="icon xs" />
    </button>
@else
    <button type="button" class="gap-05 flex-centered min-wh-2.5 py-05 px-075 ends:rounded-lg gray state-gray bdr bdr-gray-200" disabled aria-label="Next page (unavailable)">
        <span>Next</span>
        <x-gt-icon name="chevron-right" class="icon xs" />
    </button>
@endif