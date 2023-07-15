@props([
    'sortable' => null,
    'direction' => null,
    'multiColumn' => null,
    ])

    <th {{ $attributes->merge(['class' => 'fw7']) }} style="letter-spacing: .05em;">

        @unless($sortable)

            <span>{{ $slot }}</span>

        @else

            <button class="flex px-0 bdr-0 va-b">

                <span>{{ $slot }}</span>

                @if($direction === 'asc')
                    <svg class="wh-1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                @elseif($direction === 'desc')
                    <svg class="wh-1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                @else
                    <svg class="wh-1 txt-muted" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                @endif

            </button>

        @endif

    </th>
