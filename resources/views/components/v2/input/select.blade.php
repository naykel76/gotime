@props([
    'for' => null,
    'options' => [],
    'placeholder' => null,
    'componentName' => 'select',
])

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? ($for ?? null);
    if (!isset($for)) {
        throw new InvalidArgumentException("The `$componentName` component requires either a `for` or `wire:model` attribute to be set.");
    }
@endphp

<x-gotime::v2.input.partials.control-group :$for>
    @if (empty($options))
        <x-gotime::v2.input.controls.select :$for {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
            {{ $slot }}
        </x-gotime::v2.input.controls.select>
    @else
        <x-gotime::v2.input.controls.select :$for {{ $attributes->except(['label', 'help-text', 'rowClass']) }} :$options />
    @endif
</x-gotime::v2.input.partials.control-group>
