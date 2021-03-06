<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    @if (View::exists('layouts.partials.head'))
        @include('layouts.partials.head')
    @else
        @include('gotime::layouts.partials.head')
    @endif

    @yield('head')

</head>

<body>

    <div id="app">

        @if (View::exists('layouts.partials.navbar'))
            @include('layouts.partials.navbar')
        @else
            @include('gotime::layouts.partials.navbar')
        @endif

        @yield('top-a')
        @yield('top-b')

        {{-- do not add container or padding here, let the content to the work --}}
        <main id="nk-main">
            @yield('content')
        </main>

        @yield('bottom-a')
        @yield('bottom-b')

        @if (View::exists('layouts.partials.footer'))
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
