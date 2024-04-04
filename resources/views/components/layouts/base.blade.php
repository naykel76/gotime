@props(['pageTitle' => null])

<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @includeFirst(['components.layouts.partials.head', 'gotime::components.layouts.partials.head'])
    </head>

    {{-- NK::TD make analytics optional in the config --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', '{{ config('services.analytics_id') }}', {
            cookie_flags: 'SameSite=None;Secure'
        });
    </script>

    <body {{ $attributes }}>
        {{ $slot }}
        <x-gt-toast />
        @livewireScripts
        @stack('scripts')
    </body>

</html>
