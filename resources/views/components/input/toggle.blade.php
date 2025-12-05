@props([
    'for' => null,
    'componentName' => 'input.toggle',
])

@php
    $for = getFormFieldName($attributes, $for, $componentName);
@endphp

<div class="frm-row">
    <x-gotime::.input.controls.toggle :$for {{ $attributes->except(['help-text', 'helpText', 'helpTextTop', 'rowClass', 'tooltip']) }} />
</div>

