@props([
    'sortable' => null,
    'direction' => null,
    'multiColumn' => null,
    ])

    <th {{ $attributes->merge(['class' => 'fw-7']) }} style="letter-spacing: .05em;">

        @unless($sortable)

            <span class="fw-4">{{ $slot }}</span>

        @else

            <button class="flex px-0 bdr-0 fw-7 cursor-pointer">

                <span>{{ $slot }}</span>

                @if($direction === 'asc')
                    <svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                @elseif($direction === 'desc')
                    <svg class="icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                @else
                    <svg class="icon txt-muted" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                @endif

            </button>

        @endif

    </th>
