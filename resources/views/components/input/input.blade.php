@props(['controlOnly' => false])

@if($controlOnly)
    <x-gotime::input.input-base-control {{ $attributes }} />
@else
    <x-gotime::input.control-group-layout>
        <x-gotime::input.input-base-control {{ $attributes }} />
    </x-gotime::input.control-group-layout>
@endif
