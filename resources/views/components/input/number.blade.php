@props(['controlOnly' => false])

@if($controlOnly)
    <x-gotime::input.control-input {{ $attributes->merge(['type' => 'number', 'step' => 'any']) }} />
@else
    <x-gotime::input.control-group-layout>
        <x-gotime::input.control-input {{ $attributes->merge(['type' => 'number', 'step' => 'any']) }} />
    </x-gotime::input.control-group-layout>
@endif
