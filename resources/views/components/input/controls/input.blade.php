@props([
    'for' => null,
    'value' => null,
    'componentName' => 'input control',
])

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? ($for ?? null);
    if (!isset($for)) {
        throw new InvalidArgumentException("The `$componentName` component requires either a `for` or `wire:model` attribute to be set.");
    }
@endphp

<x-gotime::.input.partials.control-with-addons>
    <input name="{{ $for }}" id="{{ $for }}"
        {{ $attributes->merge([
                'class' => $errors->has($for) ? 'bdr-red z-100 placeholder-red-400' : null,
            ])->except(['for']) }}
        {{-- Output value only if old input or explicit value is present (including zero) --}}
        @if (!is_null(old($for)) || !is_null($value)) value="{{ old($for, $value) }}" @endif
        {{-- Default autocomplete and autocorrect to off, but allow overrides --}}
        autocomplete="{{ $attributes->get('autocomplete', 'off') }}"
        autocorrect="{{ $attributes->get('autocorrect', 'off') }}" />
</x-gotime::.input.partials.control-with-addons>
