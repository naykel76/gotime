@props(['for', 'option', 'options' => []])

{{-- this check needs to be in both the control, and component to make sure we cover both cases --}}
@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $for;
    if (!isset($for)) {
        throw new InvalidArgumentException('A `for` or `wire:model` attribute must be provided for this form control.');
    }
@endphp

{{-- TODO: Add support for rendering a group of checkboxes --}}
<x-gotime::v2.input.partials.control-group :$for>
    <x-gotime::v2.input.controls.checkbox :$option {{ $attributes->except(['for', 'label', 'help-text', 'rowClass']) }} />
</x-gotime::v2.input.partials.control-group>


{{-- @if (empty($options))
    <x-gotime::v2.input.controls.checkbox {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
        {{ $slot }}
    </x-gotime::v2.input.controls.checkbox>
@else
    <x-gotime::v2.input.controls.checkbox {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
@endif --}}
