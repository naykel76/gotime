@props(['mainClasses','hasAside' => true,])

@php
    if(!isset($navigation)){
        $hasAside = false;
    }
@endphp


    <x-gotime-layouts.base>

        @includeFirst(['layouts.partials.navbar', 'gotime::layouts.partials.navbar'])

        @isset($top)
            {{ $top }}
        @endisset

        @if($hasContainer) <div class="container"> @endif

            <main id="nk-main" {{ $attributes->class(['py-5-3-2', $hasAside ? 'grid cols-30:70 gg-5' : '']) }}>

                @isset($navigation)
                    <aside>
                        {{ $navigation }}
                    </aside>
                @endisset

                <div>
                    {{ $slot }}
                </div>

            </main>

        @if($hasContainer) </div> @endif

        @isset($bottom)
            {{ $bottom }}
        @endisset

        @includeFirst(['layouts.partials.footer', 'gotime::layouts.partials.footer'])

    </x-gotime-layouts.base>


