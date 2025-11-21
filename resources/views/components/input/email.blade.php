@props([
    'for' => null,
    'componentName' => 'input.email',
])

@php
    $for = getFormFieldName($attributes, $for, $componentName);
@endphp

<x-gotime::.input.partials.control-group :$for>
    <x-gotime::.input.controls.input :$for
        {{ $attributes->merge(['type' => 'email'])->except(['label', 'help-text', 'helpText', 'helpTextTop', 'rowClass', 'tooltip']) }} />
</x-gotime::.input.partials.control-group>
