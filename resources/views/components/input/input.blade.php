@props(['controlOnly' => false, 'for'])

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $attributes->get('for');
    if (!isset($for)) {
        throw new Exception('Neither `wire:model` nor the`for` attribute is set on the form control.');
    }
@endphp

@if ($controlOnly)
    <x-gotime::input.control-input {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
@else
    {{-- pass along the $for variable to make sure it is available for error handling --}}
    <x-gotime::input.partials.control-group :$for>
        <x-gotime::input.control-input {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
    </x-gotime::input.partials.control-group>
@endif
