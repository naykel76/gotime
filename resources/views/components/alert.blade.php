@props(['type' => '', 'title' => null, 'iconClass' => '', 'icon' => null])

@php
    // be sure to update the icon before the type
    $icon =
        $icon ??
        match ($type) {
            'danger' => 'exclamation-triangle',
            'warning' => 'exclamation-circle',
            'info' => 'information-circle',
            'success' => 'check-circle',
        };

    $type = match ($type) {
        'danger' => 'danger-light',
        'warning' => 'warning-light',
        'info' => 'info-light',
        'success' => 'success-light',
        default => '',
    };
@endphp

<div {{ $attributes->merge(['class' => 'bx pxy-1 bdr-2 rounded-075 flex va-c ' . $type]) }}>
    @isset($title)
        <div {{ $title->attributes->class(['bx-title']) }}>
            {{ $title }}
        </div>
    @endisset
    @if ($icon)
        <x-gt-icon name="{{ $icon }}" class="fs0 wh-3 mr-075 {{ $iconClass }}" />
    @endif
    <div>
        {{ $slot }}
    </div>
</div>
