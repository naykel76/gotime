{{--- BARE BONES ------------------------------------------------------------
A bare bones template with only the 'head' partial being imported to provide
site assets. The only predefined sections are 'content' and 'scripts' inside
the <body> tag. 100% control in the view.
---------------------------------------------------------------------------}}
<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('gotime::layouts.partials.head')
    @yield('head')
</head>

<body>
    <div id="app">
        @yield('content')
        <flash msg="{{ session('flash') }}"></flash>
    </div>

    @yield('scripts')
</body>

</html>