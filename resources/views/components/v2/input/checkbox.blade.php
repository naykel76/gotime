@props([ 'for' => null, 'controlOnly' => false, 'rowClass' => null, 'inline' => false ])

{{-- do not use the control-group-layout for this component because it is not a good fit --}}

@if($controlOnly)
    <x-gotime::v2.input.control-checkbox {{ $attributes }} />
@else
    <div class='frm-row {{ $rowClass }}'>
        <x-gotime::v2.input.control-checkbox {{ $attributes }}>
            {{ $slot }}
        </x-gotime::v2.input.control-checkbox>

        @error($for)
            <small class="txt-red" role="alert"> {{ $message }} </small>
        @enderror

    </div>
@endif


{{-- @if($controlOnly)
    <x-gotime::input-v2.control-radio {{ $attributes }} />
@else
    <x-gotime::input.control-group-layout>
        @foreach($options as $key => $option)
            <x-gotime::input-v2.control-radio {{ $attributes->merge(['value' => $key]) }} :$option />
        @endforeach
    </x-gotime::input.control-group-layout>
@endif --}}