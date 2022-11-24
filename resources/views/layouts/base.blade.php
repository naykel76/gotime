<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    @includeFirst(['layouts.partials.head', 'gotime::layouts.partials.head'])

</head>

<body {{ $attributes }}>

    {{ $slot }}

    <livewire:scripts />

    @stack('scripts')

    <x-gotime-message />

</body>

</html>

