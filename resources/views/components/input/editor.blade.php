@props(['inlineEditor' => false]) 

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $attributes->get('for');
    if (!isset($for)) {
        throw new InvalidArgumentException('The form control requires either a `for` or `wire:model` attribute to be specified.');
    }
@endphp

<x-gotime::input.partials.control-group :$for>
    @if ($inlineEditor)
        <x-gotime::input.controls.ckeditor-inline {{ $attributes }} />
    @else
        <x-gotime::input.controls.ckeditor-classic {{ $attributes }} />
    @endif
</x-gotime::input.partials.control-group>
