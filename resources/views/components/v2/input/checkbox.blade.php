@props([
    'for' => null,
    'label' => null,
    'options' => [],
    'componentName' => 'checkbox',
])
@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? ($for ?? null);
    if (!isset($for)) {
        throw new InvalidArgumentException("The `$componentName` component requires either a `for` or `wire:model` attribute to be set.");
    }
@endphp

{{-- Do not confuse this label here with the control group label; pass through
the component name and let the control group handle it --}}
<x-gotime::v2.input.partials.control-group :$for :$componentName>
    @if (empty($options))
        <x-gotime::v2.input.controls.checkbox :$for :$label
            {{ $attributes->except(['for', 'label', 'help-text', 'rowClass']) }} />
    @else
        {{-- NKTD: Add support for rendering a group of checkboxes --}}
        @dd('there is a problem with the checkbox component, it does not currently support group options');
    @endif
</x-gotime::v2.input.partials.control-group>
