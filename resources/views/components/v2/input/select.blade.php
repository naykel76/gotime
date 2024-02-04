@props(['for' => null, 'controlOnly' => false, 'placeholder' => null, 'options' => []])

@if ($controlOnly)
    <x-gotime::v2.input.control-select {{ $attributes->except(['label', 'help-text']) }}>
        {{ $slot }}
    </x-gotime::v2.input.control-select>
@else
    <x-gt-control-group>
        <x-gotime::v2.input.control-select {{ $attributes->except(['label', 'help-text']) }}>
            @foreach ($options as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </x-gotime::v2.input.control-select>
    </x-gt-control-group>
@endif
