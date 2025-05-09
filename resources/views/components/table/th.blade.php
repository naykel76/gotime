@props([
    'sortable' => null,
    'direction' => null,
    'alignCenter' => false,
    'alignRight' => false,
    'alignLeft' => false,
])

<th {{ $attributes->merge(['class' => 'fw7']) }} style="letter-spacing: .05em;">

    {{-- convert to upper case unless txt-capitalize or txt-lower class is present --}}
    @php
        $text = Str::contains($attributes->get('class'), 'txt-capitalize') ? $slot : (Str::contains($attributes->get('class'), 'txt-lower') ? $slot : Str::upper($slot));
    @endphp

    @if ($sortable)
        <button @class([
            'flex px-0 bdr-0 va-b ha-c',
            'w-full ha-l' => $alignLeft,
            'w-full ha-r' => $alignRight,
            'w-full ha-c' => $alignCenter,
        ])>
            <span>{{ $text }}</span>
            @if ($direction === 'asc')
                <x-gt-icon name="chevron-down" class="wh-1 ml-025" />
            @elseif($direction === 'desc')
                <x-gt-icon name="chevron-up" class="wh-1 ml-025" />
            @else
                <x-gt-icon name="chevron-down" class="wh-1 opacity-02 ml-05" />
            @endif
        </button>
    @else
        <span>{{ $text }}</span>
    @endif
</th>
