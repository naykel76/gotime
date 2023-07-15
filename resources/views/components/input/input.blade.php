@props(['controlOnly' => false])

    @if($controlOnly)
        <x-gotime::input.control-input {{ $attributes }} />
    @else
        <x-gotime::input.control-group-layout>
            <x-gotime::input.control-input {{ $attributes }} />
        </x-gotime::input.control-group-layout>
    @endif
