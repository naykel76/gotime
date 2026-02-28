<x-layouts.base :title="$title ?? null" :class="$bodyClass ?? null">

    @includeFirst(['components.layouts.partials.navbar', 'gotime::components.layouts.partials.navbar'])

    @isset($top)
        {{ $top }}
    @endisset

    @includeFirst(['components.layouts.partials.main', 'gotime::components.layouts.partials.main'])

    @isset($bottom)
        {{ $bottom }}
    @endisset

    @includeFirst(['components.layouts.partials.footer', 'gotime::components.layouts.partials.footer'])

</x-layouts.base>
