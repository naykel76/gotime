@props([
    'for' => null,
    'text' => null,
    'options' => [],
    'componentName' => 'checkbox',
])

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? ($for ?? null);
    if (!isset($for)) {
        throw new InvalidArgumentException("The `$componentName` component requires either a `for` or `wire:model` attribute to be set.");
    }
@endphp

<x-gotime::v2.input.partials.control-group :$for>
    @if (empty($options))
        <x-gotime::v2.input.controls.checkbox :$for :$text
            {{ $attributes->except(['for', 'label', 'help-text', 'rowClass']) }} />
    @else
        {{-- NKTD: Add support for rendering a group of checkboxes --}}
        @dd('there is a problem with the checkbox component, it does not currently support group options');
    @endif
</x-gotime::v2.input.partials.control-group>
