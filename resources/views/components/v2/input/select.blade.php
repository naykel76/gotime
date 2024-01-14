@props(['for' => null, 'controlOnly' => false, 'placeholder' => null, 'options' => [] ])

@if($controlOnly)
    <x-gotime::v2.input.control-select {{ $attributes }}>
        {{ $slot }}
    </x-gotime::v2.input.control-select>
@else
    <x-gotime::v2.input.layout-control-group>
        <x-gotime::v2.input.control-select {{ $attributes }}>

        @foreach($options as $key => $value)
        {{-- {{ dd($key) }} --}}

                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </x-gotime::v2.input.control-select>
    </x-gotime::v2.input.layout-control-group>
@endif
