@push('head')
    <meta name="robots" content="noindex,follow">
@endpush

<x-gotime-layouts.base :$pageTitle class="nk-admin relative">

    <div class="navbar">

        <div class="logo">
            <a href="{{ url('/admin') }}"><img src="/images/nk/logo-alt.svg" alt="{{ config('app.name') }}" width=125></a>
        </div>

        <div class="flex">
            <nav> <a href="/" class="btn pink mr" target="blank">Home Page</a> </nav>
            <x-authit-account-dropdown class="pos-r" btn-class="txt-white">
                <x-gt-menu menuname="user" filename="nav-admin" class="menu">
                    <x-authit::logout-link />
                </x-gt-menu>
            </x-authit-account-dropdown>
        </div>

    </div>

    {{-- NK::TD make this layout responsive --}}
    <main class="nk-main flex">

        <aside class="bdr-r py bg-gray-50 minw-20">
            @includeFirst(['components.layouts.partials.admin-nav', 'gotime::components.layouts.partials.admin-nav'])
        </aside>

        {{-- NK::REVIEW all admin views will have a container rather than a
        flag to set. This is to make it easier when setting a Livewire layout
        for a full page component. It is possible this can be changed when
        moving to livewire 3! --}}
        <div {{ $attributes->class(['container py-2']) }}>
            {{ $slot }}
        </div>

    </main>

</x-gotime-layouts.base>
