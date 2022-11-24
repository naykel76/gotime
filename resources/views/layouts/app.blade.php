<x-gotime-layouts.base>

    @if(class_exists(\Naykel\Authit\Http\Controllers\UserController::class))
        <x-gotime::top-toolbar />
    @endif

    @includeFirst(['layouts.partials.navbar', 'gotime::layouts.partials.navbar'])

    @isset($top)
    {{ $top }}
    @endisset

    @includeFirst(['layouts.partials.main', 'gotime::layouts.partials.main'])

    @isset($bottom)
        {{ $bottom }}
    @endisset

    @includeFirst(['layouts.partials.footer', 'gotime::layouts.partials.footer'])

</x-gotime-layouts.base>


