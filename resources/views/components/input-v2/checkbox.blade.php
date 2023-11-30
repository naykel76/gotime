@props([ 'for' => null, 'controlOnly' => false, 'rowClass' => null, 'inline' => false ])

{{-- do not use the control-group-layout for this component because it is not a good fit --}}

@if($controlOnly)
    <x-gotime::input-v2.control-checkbox {{ $attributes }} />
@else
    <div class='frm-row {{ $rowClass }}'>

        <x-gotime::input-v2.control-checkbox {{ $attributes }}>
            {{ $slot }}
        </x-gotime::input-v2.control-checkbox>

        @error($for)
            <small class="txt-red" role="alert"> {{ $message }} </small>
        @enderror

    </div>
@endif

