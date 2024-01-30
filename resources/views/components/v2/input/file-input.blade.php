@props(['for', 'text', 'withIcon' => true, 'default' => false])

{{-- make sure the `withIcon` value is a boolean --}}
<?php $withIcon = filter_var($withIcon, FILTER_VALIDATE_BOOLEAN); ?>

<div class="frm-row">
    @if(!$default)
        {{-- adding the `for` attribute to the label makes for a bad day, so don't do it! --}}
        <label {{ $attributes->class('file') }}>
            <input type="file" name="{{ $for }}">

            @if($withIcon)
                <svg class="wh-1 mr-075" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd" d="M11.47 2.47a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1-1.06 1.06l-3.22-3.22V16.5a.75.75 0 0 1-1.5 0V4.81L8.03 8.03a.75.75 0 0 1-1.06-1.06l4.5-4.5ZM3 15.75a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" /> </svg>
            @endif

            <span> {{ $text ?? 'Select file...' }} </span>
        </label>
    @else
        @isset($label)
            <x-gt-label :tooltip="$tooltip ?? null" />
        @endisset
        <input {{ $attributes }} wire:model="image" type="file">
    @endif
</div>
