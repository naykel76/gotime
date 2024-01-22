{{-- ------------------------------------------------------------------------
| -- $routePrefix - named route WITHOUT the 'action' (admin.courses)
| -- $h (optional) heading size (h2, h3, ...)
| -- $buttonText (optional) heading size (h2, h3, ...)
------------------------------------------------------------------------ --}}

@php
    $resource = dotLastSegment($routePrefix)
@endphp

<div class="flex space-between va-c">

    <{{ $h ?? 'h1' }} class="my-2">{{ $pageTitle }}</{{ $h ?? 'h1' }}>

    <div>
        <a class="btn primary" href="{{ route("$routePrefix.create") }}">
            <x-gt-icon name="plus-circle" class="icon" /> <span>{{ $buttonText ?? 'Add ' . ucfirst(Str::singular($resource)) }}</span>
        </a>
        {{ $slot }}
    </div>
</div>
