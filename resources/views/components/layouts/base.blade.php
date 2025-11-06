@props(['title' => null, 'nofollow' => false])

<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @if ($nofollow)
            <meta name="robots" content="noindex,follow">
        @endif
        @includeFirst(['components.layouts.partials.head', 'gotime::components.layouts.partials.head'])
    </head>

    <body {{ $attributes }}>

        {{ $slot }}

        <x-gt-toast />

        @livewireScripts
        @stack('scripts')
        
    </body>

</html>
