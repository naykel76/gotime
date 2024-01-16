@props(['controlOnly' => false])

@if($controlOnly)
    <x-gotime::input.layout-control-only  {{ $attributes->merge(['type' => 'password']) }} />
@else
    <x-gt-control-group>
        <x-gotime::input.layout-control-only  {{ $attributes->merge(['type' => 'password']) }} />
    </x-gt-control-group>
@endif
