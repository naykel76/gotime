@push('head')
    <meta name="robots" content="noindex,follow">
@endpush

<x-gotime-layouts.base :$title class="nk-admin relative">

    <div class="navbar">

        <div class="logo">
            <a href="{{ url('/admin') }}"><img src="/images/nk/logo-alt.svg" alt="{{ config('app.name') }}" width=125></a>
        </div>

        <nav> <a href="/" class="btn pink" target="blank">Home Page</a> </nav>

        <div class="hide-from-tablet">
            <svg class="icon txt-white wh-40" @click="showSidebar = !showSidebar"></svg>
        </div>

    </div>

    <main id="nk-main" class='grid cols-20:80 gg-0'>

        <aside class="bdr-r py light">
            @includeFirst(['layouts.partials.admin-nav', 'gotime::layouts.partials.admin-nav'])
        </aside>

        <div {{ $attributes->class([$hasContainer ? 'container' : 'pxy-2']) }}>
            {{ $slot }}
        </div>

    </main>

</x-gotime-layouts.base>
