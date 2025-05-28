@props([
    'for' => null,
    'label' => null, // do not confuse with the control group label
    'componentName' => 'checkbox control',
])

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? ($for ?? null);
    if (!isset($for)) {
        throw new InvalidArgumentException("The `$componentName` component requires either a `for` or `wire:model` attribute to be set.");
    }
@endphp

{{-- NKTD: Add error support, or maybe just let the control-group handle it??? --}}
@if ($label || $slot->isNotEmpty())
    <label>
@endif

<input {{ $attributes }} name="{{ $for }}"
    @checked(!$attributes->has('wire:model') && old($for)) type="checkbox" />

@if ($label || $slot->isNotEmpty())
    {{ $slot->isNotEmpty() ? $slot : $label }}
    </label>
@endif
