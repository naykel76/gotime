@props(['placeholder' => null, 'options' => []])

{{-- this check needs to be in both the control, and component to make sure we cover both cases --}}
@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $for;
    if (!isset($for)) {
        throw new InvalidArgumentException('A `for` or `wire:model` attribute must be provided for this form control.');
    }
@endphp

<x-gotime::v2.input.partials.control-group :$for>
    @if (empty($options))
        <x-gotime::v2.input.controls.select {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
            {{ $slot }}
        </x-gotime::v2.input.controls.select>
    @else
        <x-gotime::v2.input.controls.select {{ $attributes->except(['label', 'help-text', 'rowClass']) }} :$options />
    @endif
</x-gotime::v2.input.partials.control-group>
