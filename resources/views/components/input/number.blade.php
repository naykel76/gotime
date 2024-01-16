@props(['controlOnly' => false])

@if($controlOnly)
    <x-gotime::input.layout-control-only {{ $attributes->merge(['type' => 'number', 'step' => 'any']) }} />
@else
    <x-gt-control-group>
        <x-gotime::input.layout-control-only {{ $attributes->merge(['type' => 'number', 'step' => 'any']) }} />
    </x-gt-control-group>
@endif
