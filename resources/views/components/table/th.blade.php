@props([
    'sortable' => null,
    'direction' => null,
    'alignCenter' => false,
    'alignRight' => false,
    'alignLeft' => false,
])

{{-- If the `tr` data is wrapping it is likely because `td` content is NOT longer than the content
in the `td`. This will likely ba a side effect of another cell having a width of 100%. To work
around you can add `whitespace-nowrap` to the `td` that si wrapping --}}

<th {{ $attributes->merge(['class' => 'fw7']) }} style="letter-spacing: .05em;">
    @if ($sortable)
        <button @class([
            'flex px-0 bdr-0 va-b ha-c',
            'w-full ha-l' => $alignLeft,
            'w-full ha-r' => $alignRight,
            'w-full ha-c' => $alignCenter,
        ])>
            <span>{{ $slot }}</span>
            @if ($direction === 'asc')
                <x-gt-icon name="arrow-long-down" class="wh-1 ml-025" />
            @elseif($direction === 'desc')
                <x-gt-icon name="arrow-long-up" class="wh-1 ml-025" />
            @else
                <x-gt-icon name="arrows-up-down" class="wh-1 opacity-02 ml-05" />
            @endif
        </button>
    @else
        <span>{{ $slot }}</span>
    @endif
</th>
