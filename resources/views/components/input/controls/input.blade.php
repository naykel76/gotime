@props([
    'for' => null,
    'value' => null,
    'componentName' => 'input control',
])

@php
    $for = getFormFieldName($attributes, $for, $componentName);
@endphp

<x-gotime::.input.partials.control-with-addons>
    <input name="{{ $for }}" id="{{ $for }}"
        {{ $attributes->merge([
                'class' => $errors->has($for) ? 'bdr-2 bdr-red-400 placeholder-red-400' : null,
            ])->except(['for']) }}
        {{-- Output value only if old input or explicit value is present (including zero) --}}
        @if (!is_null(old($for)) || !is_null($value)) value="{{ old($for, $value) }}" @endif
        {{-- Accessibility: mark invalid inputs for screen readers --}}
        @if ($errors->has($for)) aria-invalid="true" aria-describedby="{{ $for }}-error" @endif />
</x-gotime::.input.partials.control-with-addons>