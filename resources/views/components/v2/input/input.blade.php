@props(['controlOnly' => false])

@if ($controlOnly)
    <x-gotime::v2.input.control-input {{ $attributes }} />
@else
    <x-gt-control-group>
        <x-gotime::v2.input.control-input {{ $attributes }} />
    </x-gt-control-group>
@endif
