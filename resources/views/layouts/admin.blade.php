<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    @if(View::exists('layouts.partials.head'))
        @include('layouts.partials.head')
    @else
        @include('gotime::layouts.partials.head')
    @endif

    @yield('head')

</head>

<body>

    <div id="app" class="nk-admin">

        <div class="navbar">

            <div class="logo">
                <a href="{{ url('/admin') }}"><img src="/images/nk/logo-light.svg" alt="{{ config('app.name') }}" height=40></a>
            </div>

            <nav>
                <a href="/" target="blank">Home Page</a>
            </nav>
            @if(Route::has('login'))
                <x-authit::account-dropdown />
            @endif

            <div class="hide-from-tablet">
                <svg class="icon burger txt-white wh40" @click="showSidebar = !showSidebar">
                    <use xlink:href="/svg/nk_icon-defs.svg#icon-menu"></use>
                </svg>
            </div>

        </div>

        <main id="nk-main" class="py">

            <div class="row">

                <aside class="col-lg-20 col-md-25 bdr-r flexCol">

                    <x-gotime-menu menuname="main" filename="nav-admin" class="nav" />

                </aside>

                <div class="col-lg-80 col-md-75">

                    @yield('content')

                </div>

            </div>

        </main>

        <footer id="nk-footer">

            <div class="copyright">
                <small>{{ config('naykel.copyright') }}&trade; Copyright © <?php echo date('Y'); ?> &nbsp;&nbsp; | &nbsp;&nbsp; Design and Hosting by <a href="https://naykel.com.au" target="_blank">NAYKEL</a></small>
            </div>

        </footer>

        <flash msg="{{ session('flash') }}"></flash>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')

</body>

</html>