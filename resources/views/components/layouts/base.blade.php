@props(['pageTitle' => null, 'nofollow' => false])

<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @if ($nofollow)
        <meta name="robots" content="noindex,follow">
    @endif
    @includeFirst(['components.layouts.partials.head', 'gotime::components.layouts.partials.head'])
</head>

@if (!app()->environment('local'))
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
@endif

<body {{ $attributes }}>
    {{ $slot }}
    <x-gt-toast />
    @livewireScripts
    @stack('scripts')
</body>

</html>
