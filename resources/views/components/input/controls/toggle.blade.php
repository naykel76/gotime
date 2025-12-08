@props([
    'for' => null,
    'label' => null,
    'componentName' => 'input.toggle control',
])

@php
    $for = getFormFieldName($attributes, $for, $componentName);
@endphp

<label class="toggle justify-between">
    {{ $slot->isNotEmpty() ? $slot : $label }}
    <input type="checkbox" name="{{ $for }}" id="{{ $for }}"
        {{ $attributes->merge([
                'class' => $errors->has($for) ? 'bdr-2 bdr-red-400' : null,
            ])->except(['for', 'label']) }}
        @checked(!$attributes->has('wire:model') && old($for))
        @if ($errors->has($for)) aria-invalid="true" aria-describedby="{{ $for }}-error" @endif />
    <div></div>
</label>
