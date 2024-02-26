@props(['controlOnly' => false])

@if ($controlOnly)
    <x-gotime::v2.input.control-pikaday {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
@else
    <x-gt-control-group>
        <x-gotime::v2.input.control-pikaday {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
    </x-gt-control-group>
@endif
