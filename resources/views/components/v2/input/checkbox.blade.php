@props(['for' => null, 'controlOnly' => false, 'rowClass' => null, 'ignoreErrors' => false])

{{-- do not use the control-group-layout for this component because it is not a good fit --}}

@if ($controlOnly)
    <x-gotime::v2.input.control-checkbox {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
@else
    <div class='frm-row {{ $rowClass }}'>
        <x-gotime::v2.input.control-checkbox {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
            {{ $slot }}
        </x-gotime::v2.input.control-checkbox>

        @unless ($ignoreErrors)
            @error($for)
                <small class="txt-red" role="alert"> {{ $message }} </small>
            @enderror
        @endunless

    </div>
@endif
