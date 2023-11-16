<x-gotime-layouts.base :$pageTitle>

    @includeFirst(['components.layouts.partials.header', 'gotime::components.layouts.partials.header'])

    @isset($top) {{ $top }} @endisset

    {{-- main attributes are passed in where the layout is rendered --}}
    @includeFirst(['components.layouts.partials.main', 'gotime::components.layouts.partials.main'])

    @isset($bottom) {{ $bottom }} @endisset

    @includeFirst(['components.layouts.partials.footer', 'gotime::components.layouts.partials.footer'])

</x-gotime-layouts.base>


