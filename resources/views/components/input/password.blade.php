@props(['controlOnly' => false])

@if($controlOnly)
    <x-gotime::input.layout-control-only  {{ $attributes->merge(['type' => 'password']) }} />
@else
    <x-gotime::v2.input.layout-control-group>
        <x-gotime::input.layout-control-only  {{ $attributes->merge(['type' => 'password']) }} />
    </x-gotime::v2.input.layout-control-group>
@endif
