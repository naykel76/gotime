@props(['controlOnly' => false])
{{-- do not add `for` in the props to let this do its job --}}
@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $attributes->get('for');
    if (!isset($for)) {
        throw new InvalidArgumentException('The form control requires either a `for` or `wire:model` attribute to be specified.');
    }
@endphp

@if ($controlOnly)
    <x-gotime::input.control-pikaday {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
@else
    <x-gotime::input.partials.control-group>
        <x-gotime::input.control-pikaday {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
    </x-gotime::input.partials.control-group>
@endif


