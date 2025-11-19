<x-gt-layouts.base :title="$title ?? null" :class="$bodyClass ?? null">
    
    <div class="to-md:hidden">
        <x-gt-nav filename="nav-main" menuname="main" layout="navbar" withIcons class="pink" />
    </div>

    <main {{ $attributes->except('title')->merge(['class' => 'nk-main']) }}>
        {{ $slot }}
    </main>

    @includeFirst(['components.layouts.partials.footer', 'gotime::components.layouts.partials.footer'])

</x-gt-layouts.base>
