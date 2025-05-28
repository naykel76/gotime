@props(['withIcon' => false])
{{-- do not add `for` in the props to let this do its job --}}
@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $for;
    if (!isset($for)) {
        throw new InvalidArgumentException('A `for` or `wire:model` attribute must be provided for this form control.');
    }
@endphp

<x-gotime::v2.input.partials.control-group :$for>
    <x-gotime::input.controls.flatpickr {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
        @if ($withIcon)
            <x-slot:trailingAddon>
                <x-gt-icon name="calendar" />
            </x-slot:trailingAddon>
        @endif
    </x-gotime::input.controls.flatpickr>
</x-gotime::v2.input.partials.control-group>
