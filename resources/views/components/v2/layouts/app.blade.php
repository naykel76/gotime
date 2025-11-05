<x-gt-layouts.base :title="$title ?? null" :class="$bodyClass ?? null">

    {{-- @includeFirst(['components.layouts.partials.navbar', 'gotime::components.layouts.partials.navbar']) --}}

    <main {{ $attributes->merge(['class' => 'nk-main']) }}>
        {{-- {{ $title ?? '' }} --}}
        {{ $slot }}
    </main>

    {{-- @includeFirst(['components.layouts.partials.footer', 'gotime::components.layouts.partials.footer']) --}}

</x-gt-layouts.base>
