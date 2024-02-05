@props(['controlOnly' => false])

@if ($controlOnly)
    <x-gotime::v2.input.control-pikaday {{ $attributes->except(['label', 'help-text']) }} />
@else
    <x-gt-control-group>
        <x-gotime::v2.input.control-pikaday {{ $attributes->except(['label', 'help-text']) }} />
    </x-gt-control-group>
@endif
