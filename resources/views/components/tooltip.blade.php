@props(['position'])

@php
    $position = [
        'center' => 'va-c',
        'top' => 'va-t',
        'bottom' => 'va-b',
    ][$position ?? 'center'];
@endphp

<span x-data="{ open: false }" class="relative"
    x-on:mouseenter="open=true" x-on:mouseleave="open=false">

    <div class="flex {{ $position }}">
        <x-gt-icon name="question-mark-circle" class="txt-muted icon" />
    </div>

    <div x-show="open" x-transition.duration style="display: none;"
        {{ $attributes->merge(['class' => 'absolute right-0 minw-18 z-100 bx pxy-075 mt-05 txt-xs fw4 light']) }}>
        {{ $slot }}
    </div>
</span>
