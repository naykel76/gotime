@props(['controlOnly' => false, 'for' => null, 'options' => [] ])

    @if($controlOnly)
        <x-gotime::input-v2.control-radio {{ $attributes }} />
    @else
        <x-gotime::input.control-group-layout>
            @foreach($options as $key => $option)
                <x-gotime::input-v2.control-radio {{ $attributes->merge(['value' => $key]) }} :$option />
            @endforeach
        </x-gotime::input.control-group-layout>
    @endif
