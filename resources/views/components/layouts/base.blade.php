@props(['pageTitle' => null])

<!doctype html>

    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        @includeFirst(['components.layouts.partials.head', 'gotime::components.layouts.partials.head'])

    </head>

    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config',  '{{ config('services.analytics_id') }}', {cookie_flags: 'SameSite=None;Secure'});
    </script>

    <body {{ $attributes }}>

        {{ $slot }}

        <x-gt-notification-toaster />

        @livewireScripts

        @stack('scripts')

    </body>

    </html>
