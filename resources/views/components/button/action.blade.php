@props(['action', 'icon' => null, 'iconOnly' => false, 'text' => null])

@php
    if (!$icon) {
        $icon = match ($action) {
            'edit' => 'pencil-square',
            'show' => 'eye',
            'delete' => 'trash',
            'create' => 'plus-circle',
        };
    }
@endphp

<x-gt-button.base {{ $attributes->merge(['class' => $iconOnly ? 'action-btn aspect-square' : 'action-btn']) }}>
    <x-gt-icon name="{{ $icon }}" class="wh-1.25 opacity-70" />
    @unless ($iconOnly)
        @if ($text != '' || $slot->isNotEmpty())
            <span class="ml-025 font-semibold">{{ $slot->isNotEmpty() ? $slot : $text }}</span>
        @endif
    @endunless
</x-gt-button.base>
