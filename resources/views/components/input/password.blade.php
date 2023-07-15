@props(['controlOnly' => false])

@if($controlOnly)
    <x-gotime::input.control-input  {{ $attributes->merge(['type' => 'password']) }} />
@else
    <x-gotime::input.control-group-layout>
        <x-gotime::input.control-input  {{ $attributes->merge(['type' => 'password']) }} />
    </x-gotime::input.control-group-layout>
@endif
