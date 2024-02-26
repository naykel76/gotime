@props([
    'for' => null,
    'controlOnly' => false,
    'placeholder' => null,
    'options' => [],
    'manualLoop' => false,
])

@if ($controlOnly)
    <x-gotime::v2.input.control-select {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
        {{ $slot }}
    </x-gotime::v2.input.control-select>
@elseif($manualLoop)
    <x-gt-control-group>
        <x-gotime::v2.input.control-select {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
            {{ $slot }}
        </x-gotime::v2.input.control-select>
    </x-gt-control-group>
@else
    <x-gt-control-group>
        <x-gotime::v2.input.control-select {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
            @foreach ($options as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </x-gotime::v2.input.control-select>
    </x-gt-control-group>
@endif
