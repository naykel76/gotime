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
            @includeFirst(['components.layouts.partials.top-toolbar', 'gotime::components.layouts.partials.top-toolbar'])
        @endif
    @endif

    @includeFirst(['components.layouts.partials.navbar', 'gotime::components.layouts.partials.navbar'])

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

    @includeFirst(['components.layouts.partials.footer', 'gotime::components.layouts.partials.footer'])

</x-gotime-layouts.base>


