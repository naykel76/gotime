@props(['controlOnly' => false])

@if($controlOnly)
    <x-gotime::input.control-input {{ $attributes->merge(['type' => 'email']) }} />
@else
    <x-gotime::input.control-group-layout>
        <x-gotime::input.control-input {{ $attributes->merge(['type' => 'email']) }} />
    </x-gotime::input.control-group-layout>
@endif
