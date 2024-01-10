@props(['controlOnly' => false])

@if($controlOnly)
    <x-gotime::v2.input.control-input {{ $attributes }} />
@else
    <x-gotime::v2.input.layout-control-group>
        <x-gotime::v2.input.control-input {{ $attributes }} />
    </x-gotime::v2.input.layout-control-group>
@endif
