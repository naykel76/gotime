@props([
    'for' => null,
    'label' => null, // do not confuse with the control group label
    'rowClass' => null,
    'ignoreErrors' => false,
])

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $for;

    if (!isset($for)) {
        throw new InvalidArgumentException("The `$componentName` component requires either a `for` or `wire:model` attribute to be set.");
    }
@endphp

<div class='frm-row {{ $rowClass }}'>
    <label>
        <input {{ $attributes }} name="{{ $for }}" @checked(!$attributes->has('wire:model') && old($for)) type="checkbox" />
        {{ $slot->isNotEmpty() ? $slot : $label }}
    </label>

    @error($for)
        <small class="txt-red" role="alert"> {{ $message }} </small>
    @enderror
</div>
