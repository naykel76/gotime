@props(['inlineEditor' => false]) 

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $for;
    if (!isset($for)) {
        throw new InvalidArgumentException('A `for` or `wire:model` attribute must be provided for this form control.');
    }
@endphp

<x-gotime::v2.input.partials.control-group :$for>
    @if ($inlineEditor)
        <x-gotime::input.controls.ckeditor-inline {{ $attributes }} />
    @else
        <x-gotime::input.controls.ckeditor-classic {{ $attributes }} />
    @endif
</x-gotime::v2.input.partials.control-group>
