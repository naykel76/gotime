@props(['option'])

{{-- this check needs to be in both the control, and component to make sure we cover both cases --}}
@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $for;
    if (!isset($for)) {
        throw new InvalidArgumentException('A `for` or `wire:model` attribute must be provided for this form control.');
    }
@endphp

<label>
    <input
        {{ $attributes }} name="{{ $for }}"
        @checked(!$attributes->has('wire:model') && old($for))
        type="checkbox" />
    {{ $slot->isNotEmpty() ? $slot : $option }}
</label>