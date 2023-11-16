<div id="nk-header">

    <div class="container flex space-between">

        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ config('naykel.logo.path') }}" height="{{ config('naykel.logo.height') }}" alt="{{ config('app.name') }}">
            </a>
        </div>

        <div class="md:hide mxy-0">
            <x-gt-sidebar layout="burger-button-main" />
        </div>

    </div>

</div>

<div class="navbar to-md:hide">

    <div class="container">

        <x-gt-menu menuname="main" class="">

            @can('access admin')
                <a href="{{ route('admin') }}" class="btn info mr">Admin</a>
            @endcan

        </x-gt-menu>

    </div>

</div>
