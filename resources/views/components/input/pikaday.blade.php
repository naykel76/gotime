@props(['controlOnly' => false])
{{-- do not add `for` in the props to let this do its job --}}
@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $for;
    if (!isset($for)) {
        throw new InvalidArgumentException('A `for` or `wire:model` attribute must be provided for this form control.');
    }
@endphp

@if ($controlOnly)
    <x-gotime::input.controls.pikaday {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
@else
    <x-gotime::v2.input.partials.control-group :$for>
        <x-gotime::input.controls.pikaday {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
    </x-gotime::v2.input.partials.control-group>
@endif


