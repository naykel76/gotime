@aware(['text' => '', 'icon' => false, 'iconClass' => '', 'iconPosition' => 'left'])

@php
    $position = match ($iconPosition) {
        'right' => 'order-1',
        default => 'order-0',
    };
    $margin = match ($iconPosition) {
        'right' => 'ml-05',
        default => 'mr-05',
    };
@endphp

<button {{ $attributes->merge(['type' => 'button']) }}>

    @if ($icon)
        <x-gt-icon name="{{ $icon }}"
            class="icon {{ $iconClass }} {{ !$text ? '' : $margin }} {{ $position }}" />
    @endif

    @if ($text != '' || $slot->isNotEmpty())
        {{-- Use the slot if available, instead of text. Avoid wrapping this in a span or
        other element giving you full control over the slot for easier styling.  --}}
        {{ $slot->isNotEmpty() ? $slot : ($text != '' ? $text : '') }}
    @endif

</button>
