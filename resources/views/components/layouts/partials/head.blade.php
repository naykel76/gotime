<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ isset($pageTitle) ? Str::title($pageTitle) . ' | ' . config('app.name') : config('app.name') }}</title>

<link rel="icon" type="image/x-icon" href="{{ config('gotime.favicon') }}">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

@vite(['resources/scss/app.scss', 'resources/js/app.js'])

@livewireStyles

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

@stack('head')
@stack('styles')
