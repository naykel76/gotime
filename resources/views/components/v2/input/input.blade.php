@props([
    'for' => null,
    'componentName' => 'input',
])

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? ($for ?? null);
    if (!isset($for)) {
        throw new InvalidArgumentException("The `$componentName` component requires either a `for` or `wire:model` attribute to be set.");
    }
@endphp

<x-gotime::v2.input.partials.control-group :$for>
    <x-gotime::v2.input.controls.input :$for
        {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
</x-gotime::v2.input.partials.control-group>
