@props([
    'for' => null,
    'placeholder' => null,
    'options' => [],
    'componentName' => 'select control',
])

@php
    $for = getFormFieldName($attributes, $for, $componentName);
@endphp

<select name="{{ $for }}" id="{{ $for }}"
    {{ $attributes->merge([
            'class' => $errors->has($for) ? 'bdr-2 bdr-red-400 placeholder-red-400' : null,
        ])->except(['for']) }}
    {{-- Accessibility: mark invalid selects for screen readers --}}
    @if ($errors->has($for)) aria-invalid="true" aria-describedby="{{ $for }}-error" @endif>

    @unless ($attributes->has('multiple'))
        <option disabled selected value="">{{ $placeholder ?? 'Please select...' }}</option>
    @endunless

    @if (empty($options))
        {{ $slot }}
    @else
        @foreach ($options as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    @endif
</select>
