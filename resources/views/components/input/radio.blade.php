@props([
    'for' => null,
    'value' => null,
    'label' => null,
    'rowClass' => null,
    'ignoreErrors' => false,
])

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $for;

    if (!isset($for)) {
        throw new InvalidArgumentException("The `radio` component requires either a `for` or `wire:model` attribute to be set.");
    }
@endphp

<div class='frm-row {{ $rowClass }}'>
    <label>
        <input 
            {{ $attributes }} 
            name="{{ $for }}" 
            value="{{ $value }}"
            @checked(!$attributes->has('wire:model') && old($for) == $value)
            type="radio" />
        {{ $slot->isNotEmpty() ? $slot : $label }}
    </label>

    @if (!$ignoreErrors)
        @error($for)
            <small class="txt-red" role="alert"> {{ $message }} </small>
        @enderror
    @endif
</div>
