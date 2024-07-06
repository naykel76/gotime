@props(['controlOnly' => false])
{{-- do not add `for` in the props to let this do its job --}}
@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $attributes->get('for');
    if (!isset($for)) {
        throw new InvalidArgumentException('This form control must have at least one of the following attributes specified: `wire:model` or `for`. ');
    }
@endphp

@if ($controlOnly)
    <x-gotime::input.control-input {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
@else
    <x-gotime::input.partials.control-group :$for>
        <x-gotime::input.control-input {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
    </x-gotime::input.partials.control-group>
@endif
