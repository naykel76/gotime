@push('head')
    <meta name="robots" content="noindex,follow">
@endpush

<x-gotime-layouts.base :$title class="nk-admin relative">

    <div class="navbar">

        <div class="logo">
            <a href="{{ url('/admin') }}"><img src="/images/nk/logo-alt.svg" alt="{{ config('app.name') }}" width=125></a>
        </div>

        <div class="flex">

            <nav> <a href="/" class="btn pink mr" target="blank">Home Page</a> </nav>

            <x-authit-account-dropdown class="pos-r" btn-class="txt-white">

                <x-gt-menu-new menuname="user" filename="nav-admin" class="menu">

                    <x-authit::logout-link />

                </x-gt-menu-new>

            </x-authit-account-dropdown>

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
