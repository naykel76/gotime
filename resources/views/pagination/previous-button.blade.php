@if ($paginator->onFirstPage())
    <button type="button" class="gap-05 flex-centered min-wh-2.5 py-05 px-075 ends:rounded-lg gray state-gray bdr bdr-gray-200" disabled aria-label="Previous page (unavailable)">
        <x-gt-icon name="chevron-left" class="icon xs" />
        <span>Previous</span>
    </button>
@else
    <button wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}"
        wire:loading.attr="disabled" type="button" class="gap-05 flex-centered min-wh-2.5 py-05 px-075 ends:rounded-lg gray state-gray bdr bdr-gray-200" aria-label="Go to previous page">
        <x-gt-icon name="chevron-left" class="icon xs" />
        <span>Previous</span>
    </button>
@endif