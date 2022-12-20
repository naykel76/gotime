@push('head')
    <meta name="robots" content="noindex,follow">
@endpush

@props(['hasContainer' => true])

    <x-gotime-layouts.base class="nk-admin relative">

        {{-- <div class="ml-auto pxy-1">
            @auth
                <x-authit-account-dropdown />
            @endauth
        </div> --}}

        <div class="navbar">

            <div class="logo">
                <a href="{{ url('/admin') }}"><img src="/images/nk/logo-alt.svg" alt="{{ config('app.name') }}" height=40></a>
            </div>

            <nav> <a href="/" class="btn pink" target="blank">Home Page</a> </nav>

            <div class="hide-from-tablet">
                <svg class="icon txt-white wh-40" @click="showSidebar = !showSidebar"></svg>
            </div>

        </div>

        <main id="nk-main" {{ $attributes->class(['grid cols-20:80 gg-0']) }}>

            <aside class="bdr-r py light">
                <x-gotime-menu menuname="main" filename="nav-admin" useIcons=true class="menu" />
            </aside>

            <div class="pxy-2 {{ $hasContainer ? 'container' : '' }}">
                {{ $slot }}
            </div>

        </main>

        <x-gotime-notification-toaster />

    </x-gotime-layouts.base>
