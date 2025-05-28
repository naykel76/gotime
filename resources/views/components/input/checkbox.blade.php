@props(['controlOnly' => false, 'rowClass' => null, 'ignoreErrors' => false])

{{-- do not add `for` in the props to let this do its job --}}
@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $for;
    if (!isset($for)) {
        throw new Exception('Neither `wire:model` nor the`for` attribute is set on the form control.');
    }
@endphp

{{-- do not use the control-group-layout for this component because it is not a good fit --}}

@if ($controlOnly)
    <x-gotime::input.control-checkbox {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
@else
    <div class='frm-row {{ $rowClass }}'>
        <x-gotime::input.control-checkbox {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
            {{ $slot }}
        </x-gotime::input.control-checkbox>

        @unless ($ignoreErrors)
            @error($for)
                <small class="txt-red" role="alert"> {{ $message }} </small>
            @enderror
        @endunless

    </div>
@endif
