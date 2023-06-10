@props(['pageTitle' => null])

<!doctype html>

    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        @includeFirst(['layouts.partials.head', 'gotime::layouts.partials.head'])

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

        <livewire:scripts />

        @stack('scripts')

    </body>

    </html>
