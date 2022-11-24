{{-- for simplicity the navbar is outside the header --}}
{{-- or need to have seperate div for header top --}}
<div id="nk-header">

    <div class="container flex space-between">
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ config('naykel.logo.path') }}" height="{{ config('naykel.logo.height') }}" alt="{{ config('app.name') }}">
            </a>
        </div>

        <div class="flex hide-up-to-tablet va-t">

            @if(Route::has('login'))
                @include('gotime::layouts.partials.login-register')
            @endif

        </div>

        <x:gotime::layouts.burger-button-with-sidebar-main>
            {{-- can add addition content or menus here --}}
        </x:gotime::layouts.burger-button-with-sidebar-main>

    </div>

</div>

<div class="navbar hide-up-to-tablet">

    <div class="container">

        <x-gotime-menu menuname="main" class="">

            @can('access admin')
                <a href="{{ route('admin') }}" class="btn info mr">Admin</a>
            @endcan

        </x-gotime-menu>

    </div>

</div>
