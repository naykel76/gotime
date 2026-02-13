@props(['title' => null, 'nofollow' => false])

<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @if (app()->environment('staging') || $nofollow )
            <meta name="robots" content="noindex,follow">
        @endif
        @includeFirst(['components.layouts.partials.head', 'gotime::components.layouts.partials.head'])
    </head>

    <body {{ $attributes }}>

        {{ $slot }}

        <x-gt-toast />

        @livewireScriptConfig(['navigate' => false])

        @stack('scripts')

    </body>

</html>
