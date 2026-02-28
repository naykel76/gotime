@props([
    'for' => null,
    'options' => [],
    'placeholder' => null,
    'componentName' => 'slim-select',
])

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? ($for ?? null);
    if (!isset($for)) {
        throw new InvalidArgumentException("The `$componentName` component requires either a `for` or `wire:model` attribute to be set.");
    }
@endphp

<x-gotime::.input.partials.control-group :$for>
    @if (empty($options))
        <x-gotime::.input.controls.slim-select :$for {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
            {{ $slot }}
        </x-gotime::.input.controls.slim-select>
    @else
        <x-gotime::.input.controls.slim-select :$for {{ $attributes->except(['label', 'help-text', 'rowClass']) }} :$options />
    @endif
</x-gotime::.input.partials.control-group>
