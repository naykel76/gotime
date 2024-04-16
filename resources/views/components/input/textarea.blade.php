@props(['controlOnly' => false])
{{-- do not add `for` in the props to let this do its job --}}
@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $attributes->get('for');
    if (!isset($for)) {
        throw new Exception('Neither `wire:model` nor the`for` attribute is set on the form control.');
    }
@endphp

@if ($controlOnly)
    <textarea {{ $for ? "name=$for id=$for" : null }}
        {{ $attributes->class(['bdr-red' => $errors->has($for)]) }}>
    </textarea>
@else
    <x-gotime::input.partials.control-group>
        <textarea {{ $for ? "name=$for id=$for" : null }} {{ $attributes->class(['bdr-red' => $errors->has($for)]) }}> </textarea>
    </x-gotime::input.partials.control-group>
@endif
