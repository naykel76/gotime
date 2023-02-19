<x-gotime-layouts.base :$title>

    @if(class_exists(\Naykel\Devit\DevitServiceProvider::class))
        @includeIf('devit::components.dev-toolbar')
    @else
        @if(config('naykel.allow_register') && Route::has('login'))
            @includeFirst(['layouts.partials.top-toolbar', 'gotime::layouts.partials.top-toolbar'])
        @endif
    @endif

    @includeFirst(['layouts.partials.navbar', 'gotime::layouts.partials.navbar'])

    @isset($top) {{ $top }} @endisset

    @includeFirst(['layouts.partials.main', 'gotime::layouts.partials.main'])

    @isset($bottom) {{ $bottom }} @endisset

    @includeFirst(['layouts.partials.footer', 'gotime::layouts.partials.footer'])

</x-gotime-layouts.base>
