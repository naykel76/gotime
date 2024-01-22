@props([ 'for' => null, 'controlOnly' => false, 'options' => [] ])

@if($controlOnly)
    <x-gotime::v2.input.control-radio {{ $attributes }} />
@else
    <x-gotime::v2.input.partials.control-group>
        @foreach($options as $key => $option)
            <x-gotime::v2.input.control-radio {{ $attributes->merge(['value' => $key]) }} :$option />
        @endforeach
    </x-gotime::v2.input.partials.control-group>
@endif
