@props([
    'for' => null,
    'componentName' => 'input.toggle',
])

@php
    $for = getFormFieldName($attributes, $for, $componentName);
@endphp

<x-gotime::.input.partials.form-row {{ $attributes->merge(['class' => $rowClass]) }}>
    <x-gotime::.input.controls.toggle :$for {{ $attributes->except(['help-text', 'helpText', 'helpTextTop', 'rowClass', 'tooltip']) }} />
</x-gotime::.input.partials.form-row>
