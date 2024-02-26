@props(['controlOnly' => false, 'for'])

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $attributes->get('for');
    if (!isset($for)) {
        throw new Exception('Neither `wire:model` nor the`for` attribute is set on the form control.');
    }
@endphp

@if ($controlOnly)
    <x-gotime::v2.input.control-input {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
@else
    {{-- pass along the $for variable to make sure it is available for error handling --}}
    <x-gt-control-group :$for>
        <x-gotime::v2.input.control-input {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
    </x-gt-control-group>
@endif
