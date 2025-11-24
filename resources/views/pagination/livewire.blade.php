@php
    if (!isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet = $scrollTo !== false ? "(\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()" : '';
@endphp

@if ($paginator->hasPages())
    <div class="flex justify-between items-center txt-sm">
        {{-- mobile buttons: show < md, hide >= md --}}
        <div class="flex justify-between items-center w-full md:hidden">
            @include('gotime::pagination.previous-button')
            @include('gotime::pagination.next-button')
        </div>
        
        {{-- results count: hide < lg, show >= lg --}}
        <p class="hidden lg:block">
            Showing <span class="fw6">{{ $paginator->firstItem() }}</span>
            to <span class="fw6">{{ $paginator->lastItem() }}</span>
            of <span class="fw6">{{ $paginator->total() }}</span> results
        </p>
        
        {{-- desktop pagination: hide < md, show >= md --}}
        <nav class="hidden md:flex items-center -space-x-px" aria-label="Pagination">
            @include('gotime::pagination.previous-button')
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="flex-centered min-wh-2.5 py-05 px-075 ends:rounded-lg gray state-gray bdr bdr-gray-200" aria-hidden="true">{{ $element }}</span>
                @endif
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button type="button" class="flex-centered min-wh-2.5 py-05 px-075 ends:rounded-lg gray state-gray bdr bdr-gray-200" aria-current="page" aria-label="Page {{ $page }}, current page">
                                {{ $page }}
                            </button>
                        @else
                            <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled"
                                aria-label="Go to page {{ $page }}" type="button" class="flex-centered min-wh-2.5 py-05 px-075 ends:rounded-lg gray state-gray bdr bdr-gray-200">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach
            @include('gotime::pagination.next-button')
        </nav>
    </div>
@endif