{{-- ------------------------------------------------------------------------
| -- $routePrefix - named route WITHOUT the 'action' (admin.courses)
| -- $h (optional) heading size (h2, h3, ...)
| -- $buttonText (optional) heading size (h2, h3, ...)
------------------------------------------------------------------------ --}}

@php
    $resource = dotLastSegment($routePrefix)
@endphp

<div class="flex space-between va-c">

    <{{ $h ?? 'h1' }} class="my-2">{{ $title }}</{{ $h ?? 'h1' }}>

    <a class="btn primary" href="{{ route("$routePrefix.create") }}">
        <x-gt-icon-plus-round class="icon" /> <span>{{ $buttonText ?? 'Add ' . ucfirst(Str::singular($resource))}}</span>
    </a>

</div>

