@props(['for' => null, 'controlOnly' => false, 'rowClass' => null, 'inline' => false])

{{-- do not use the control-group-layout for this component because it is not a good fit --}}

@if ($controlOnly)
    <x-gotime::v2.input.control-checkbox {{ $attributes->except(['label', 'help-text']) }} />
@else
    <div class='frm-row {{ $rowClass }}'>
        <x-gotime::v2.input.control-checkbox {{ $attributes->except(['label', 'help-text']) }}>
            {{ $slot }}
        </x-gotime::v2.input.control-checkbox>

        @error($for)
            <small class="txt-red" role="alert"> {{ $message }} </small>
        @enderror

    </div>
@endif
