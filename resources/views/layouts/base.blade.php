<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    @includeFirst(['layouts.partials.head', 'gotime::layouts.partials.head'])

</head>

<body {{ $attributes }}>

    {{ $slot }}

    <x-gt-notification-toaster />

    <livewire:scripts />

    @stack('scripts')

</body>

</html>
