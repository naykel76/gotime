<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ isset($title) ? "$title - " . config('app.name') : config('app.name') }}</title>

<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/nk_jtb.css') }}">
<link rel="icon" type="image/x-icon" href="{{ config('naykel.icon') ?? asset('favicon.ico') }}">

@yield('head')