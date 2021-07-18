<div id="nk-header">

    <div class="container flex ha-between py">
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ config('naykel.logo.path') }}" height="{{ config('naykel.logo.height') }}" alt="{{ config('app.name') }}">
            </a>
        </div>

        <div class="flex hide-up-to-tablet va-t">

            <div class="btn-secondary outline px-05 mr-05">
                <svg class="icon">
                    <use xlink:href="/svg/nk_icon-defs.svg#icon-cart"></use>
                </svg>
            </div>

            @if(config('naykel.account.register'))
                @auth
                    <x-authit::account-dropdown />
                @else
                    <x-authit::login-register />
                @endauth
            @endif

        </div>

        <div class="hide-from-tablet">

            <svg class="icon burger txt-white wh40" @click="showSidebar = !showSidebar">
                <use xlink:href="/svg/nk_icon-defs.svg#icon-menu"></use>
            </svg>

        </div>
    </div>

    <div class="navbar">

        <div class="container">

            <x-gotime-menu menuname="main" class="hide-up-to-tablet">
                @can('access admin')
                    <a href="{{ route('admin') }}" class="btn-info mr">Admin</a>
                @endcan
            </x-gotime-menu>

        </div>

    </div>

    @yield('header-bottom')

</div>

<sidebar :showing="showSidebar" @close="showSidebar = false">

    <p>This is the navbar sidebar!</p>

</sidebar>