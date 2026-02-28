@props([
    'for' => null,
    'options' => [],
    'placeholder' => null,
    'componentName' => 'select',
])

@php
    $for = getFormFieldName($attributes, $for, $componentName);
@endphp

<x-gotime::.input.partials.control-group :$for>
    @if (empty($options))
        <x-gotime::.input.controls.select :$for {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
            {{ $slot }}
        </x-gotime::.input.controls.select>
    @else
        <x-gotime::.input.controls.select :$for {{ $attributes->except(['label', 'help-text', 'rowClass']) }} :$options />
    @endif
</x-gotime::.input.partials.control-group>
