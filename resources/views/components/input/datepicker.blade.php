@props(['withIcon' => false])
{{-- do not add `for` in the props to let this do its job --}}
@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $attributes->get('for');
    if (!isset($for)) {
        throw new InvalidArgumentException('The form control requires either a `for` or `wire:model` attribute to be specified.');
    }
@endphp

<x-gotime::input.partials.control-group :$for>
    <x-gotime::input.controls.flatpickr {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
        @if ($withIcon)
            <x-slot:trailingAddon>
                <x-gt-icon name="calendar" />
            </x-slot:trailingAddon>
        @endif
    </x-gotime::input.controls.flatpickr>
</x-gotime::input.partials.control-group>
