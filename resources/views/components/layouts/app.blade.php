<x-gt-layouts.base :title="$title ?? null" :class="$bodyClass ?? null">

    <x-gt-nav filename="nav-main" menuname="main" layout="navbar" withIcons/>

    <main {{ $attributes->merge(['class' => 'nk-main']) }}>
        {{ $slot }}
    </main>

    @includeFirst(['components.layouts.partials.footer', 'gotime::components.layouts.partials.footer'])

</x-gt-layouts.base>
