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

<x-gotime::.input.partials.control-group :$for>
    <x-gotime::.input.controls.input :$for
        {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
</x-gotime::.input.partials.control-group>
