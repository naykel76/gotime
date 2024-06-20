@props(['type' => 'danger', 'iconClass' => ''])

@php
    // be sure to update the icon before the type
    $icon = match ($type) {
        'danger' => 'exclamation-triangle',
        'warning' => 'exclamation-circle',
        'info' => 'information-circle',
        'success' => 'check-circle',
        default => '',
    };
    $type = match ($type) {
        'danger' => 'danger-light',
        'warning' => 'warning-light',
        'info' => 'info-light',
        'success' => 'success-light',
        default => '',
    };

@endphp

<div @class(['bx bdr-2 rounded-075 flex va-c ' . $type])>
    <x-gt-icon name="{{ $icon }}" class="fs0 mr-075 {{ $iconClass }}" />
    {{ $slot }}
</div>
