@props(['controlOnly' => false])

@if($controlOnly)
    <x-gotime::input.layout-control-only {{ $attributes->merge(['type' => 'number', 'step' => 'any']) }} />
@else
    <x-gotime::v2.input.layout-control-group>
        <x-gotime::input.layout-control-only {{ $attributes->merge(['type' => 'number', 'step' => 'any']) }} />
    </x-gotime::v2.input.layout-control-group>
@endif
