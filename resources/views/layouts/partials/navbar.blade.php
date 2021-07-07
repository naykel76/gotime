<div class="navbar">

    <div class="container va-t">

        <div class="logo">
            <a href="{{ url('/') }}"><img src="{{ config('naykel.logo') }}" alt="{{ config('app.name') }}"></a>
        </div>

        {{-- main navigation --}}
        {{-- <x-gotime-menu menuname="main" class="hide-up-to-tablet" /> --}}
        <x-gotime-menu menuname="main" class="hide-up-to-tablet">
            {{-- if admin route exists and authorised user then show admin link --}}
            {{-- @if(Route::has('admin'))
                @hasanyrole('super|admin')
                    <a href="{{ route('admin') }}" class="btn-info">Admin</a>
                @endhasanyrole
            @endif --}}
        </x-gotime-menu>

        @if(1 == 2)
            @if(Route::has('login'))
                <h1>kjhdsffkjh</h1>
                @auth
                    {{-- <x-authit::account-actions /> --}}
                @else
                    {{-- <x-authit::login-register /> --}}
                @endauth
            @endif
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