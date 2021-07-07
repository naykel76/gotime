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

    <div id="app">

        @if(View::exists('layouts.partials.navbar'))
            @include('layouts.partials.navbar')
        @else
            @include('gotime::layouts.partials.navbar')
        @endif

        @yield('top-a')
        @yield('top-b')

        <main id="nk-main" class="py">

            <div class="row">

                <aside class="col-lg-20 col-md-25 bdr-r">

                    @yield('aside')

                </aside>

                <div class="col-lg-80 col-md-75">

                    @yield('content')

                </div>

            </div>

        </main>

        @yield('bottom-a')
        @yield('bottom-b')

        @if(View::exists('layouts.partials.footer'))
            @include('layouts.partials.footer')
        @else
            @include('gotime::layouts.partials.footer')
        @endif


        <flash msg="{{ session('flash') }}"></flash>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')

</body>

</html>
