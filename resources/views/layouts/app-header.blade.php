<x-gotime-layouts.base :title="$title">

    @includeFirst(['layouts.partials.header', 'gotime::layouts.partials.header'])

    @isset($top) {{ $top }} @endisset

    {{-- main attributes are passed in where the layout is rendered --}}
    @includeFirst(['layouts.partials.main', 'gotime::layouts.partials.main'])

    @isset($bottom) {{ $bottom }} @endisset

    @includeFirst(['layouts.partials.footer', 'gotime::layouts.partials.footer'])

</x-gotime-layouts.base>


