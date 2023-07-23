@props(['mainClasses','hasAside' => true,])

<x-gotime-layouts.base :$pageTitle>

    @php
        if(!isset($navigation)){
            $hasAside = false;
        }
    @endphp

    @if(class_exists(\Naykel\Devit\DevitServiceProvider::class))
        @includeIf('devit::components.dev-toolbar')
    @else
        @if(config('naykel.allow_register') && Route::has('login'))
            @includeFirst(['layouts.partials.top-toolbar', 'gotime::layouts.partials.top-toolbar'])
        @endif
    @endif

    @includeFirst(['layouts.partials.navbar', 'gotime::layouts.partials.navbar'])

    @isset($top)
        {{ $top }}
    @endisset

    @if($hasContainer) <div class="container"> @endif

        <main id="nk-main" {{ $attributes->class(['py-5-3-2-2', $hasAside ? 'grid cols-30:70 gg-5' : '']) }}>

            @isset($navigation)
                <aside {{ $navigation->attributes }}>
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


