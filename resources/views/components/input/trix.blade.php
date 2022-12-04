@props([ 'for' => null, 'label' => null, 'helpText' => null, 'rowClass' => null, 'inline' => false, 'req' => false, 'initialValue' => null, ])

@php
    // if wire:model exists even if it is null then isLivewire = true
    $isLivewire = !empty($attributes->wire('model')->value) ? true : false;
@endphp

<div class='frm-row {{ $inline ? 'inline' : '' }} {{ $rowClass }}'
    x-data="{
        @if($isLivewire)
            value: @entangle($attributes->wire('model')),
        @else
            value: '<?= $initialValue ?>',
        @endif
            isFocused() { return document.activeElement !== this.$refs.trix },
            setValue() { this.$refs.trix.editor.loadHTML(this.value) },
        }"
    x-init="setValue(); $watch('value', () => isFocused() && setValue())"
    x-on:trix-change="value = $event.target.value"
    wire:ignore
>

    @isset($label)
        <label @error($for) class="txt-red fw9 " @enderror for="{{ $for }}">
            {{ Str::title($label) }} @if ($req) <span class='txt-red'>*</span> @endif </label>
    @endisset

    <div  {{ $attributes->class(['fullwidth'])->whereDoesntStartWith('wire:model') }}>

        @isset($helpText)
            <div class="help my-05 txt-muted"> <small>{{ $helpText }}</small> </div>
        @endisset

        <input id="{{ $for }}" type="hidden" name="{{ $for }}">

        <trix-editor x-ref="trix" input="{{ $for }}" ></trix-editor>

        @error($for)
            <small class="txt-red" role="alert"> {{ $message }} </small>
        @enderror

    </div>

</div>

@once
    @push('head')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@1.3.1/dist/trix.css">
    @endpush
    @push('scripts')
        <script src="https://unpkg.com/trix@1.3.1/dist/trix.js"></script>
    @endpush
@endonce
