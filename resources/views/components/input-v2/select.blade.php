@props(['for' => null, 'controlOnly' => false, 'placeholder' => null, 'options' => [] ])

    @if($controlOnly)
        <x-gotime::input-v2.control-select {{ $attributes }}>
            {{ $slot }}
        </x-gotime::input-v2.control-select>
    @else

        <x-gotime::input.control-group-layout>
            <x-gotime::input-v2.control-select {{ $attributes }}>
                @foreach($options as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </x-gotime::input-v2.control-select>
        </x-gotime::input.control-group-layout>
    @endif
