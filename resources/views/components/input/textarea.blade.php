@props([
    'for' => null,
    'componentName' => 'textarea',
])

@php
    $for = getFormFieldName($attributes, $for, $componentName);
@endphp

<x-gotime::.input.partials.control-group :$for>
    <x-gotime::.input.controls.textarea :$for {{ $attributes->except(['label', 'help-text', 'helpText', 'helpTextTop', 'rowClass', 'tooltip']) }} />
</x-gotime::.input.partials.control-group>
