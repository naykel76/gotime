<div class="navbar">

    <div class="container va-t">

        <div class="logo">
            <a href="{{ url('/') }}"><img src="{{ config('naykel.logo') }}" alt="{{ config('app.name') }}"></a>
        </div>

        {{-- main navigation --}}
        {{-- <x-gotime-menu menuname="main" class="hide-up-to-tablet" /> --}}
        <x-gotime-menu menuname="main" class="hide-up-to-tablet"> </x-gotime-menu>

        {{-- if auth user can access admin, show button --}}

            @can('access admin')
                <a href="{{ route('admin') }}" class="btn-info">Admin</a>
            @endcan

            @if(Route::has('login'))
                @auth
                    <x-authit::account-actions />
                @else
                    <x-authit::login-register />
                @endauth
            @endif

            <div class="hide-from-tablet">
                <svg class="icon burger txt-white wh40" @click="showSidebar = !showSidebar">
                    <use xlink:href="/svg/nk_icon-defs.svg#icon-menu"></use>
                </svg>
            </div>

    </div>

</div>

<sidebar :showing="showSidebar" @close="showSidebar = false">

    <p>This is the navbar sidebar!</p>

</sidebar>