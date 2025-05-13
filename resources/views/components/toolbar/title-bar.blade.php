@php
    $resource = dotLastSegment($routePrefix);
@endphp

<div class="flex space-between va-c">

    <{{ $h ?? 'h1' }}>{{ $pageTitle }}</{{ $h ?? 'h1' }}>

        <div>
            <a class="btn primary" href="{{ route("$routePrefix.create") }}">
                <x-gt-icon name="plus-circle" class="icon" />
                <span>{{ $buttonText ?? 'Add ' . ucfirst(Str::singular($resource)) }}</span>
            </a>
            {{ $slot }}
        </div>
</div>
