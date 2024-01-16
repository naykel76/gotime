@props(['controlOnly' => false])

@if($controlOnly)
    <x-gotime::input.layout-control-only {{ $attributes->merge(['type' => 'email']) }} />
@else
    <x-gt-control-group>
        <x-gotime::input.layout-control-only {{ $attributes->merge(['type' => 'email']) }} />
    </x-gt-control-group>
@endif
