<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ isset($title) ? "$title | " . config('app.name') : config('app.name') }}</title>

<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

@vite(['resources/scss/app.scss', 'resources/js/app.js'])

<livewire:styles />

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

@stack('head')
@stack('styles')
