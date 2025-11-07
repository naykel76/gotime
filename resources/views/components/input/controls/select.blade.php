@props([
    'placeholder' => null,
    'options' => [],
    'componentName' => 'select control',
])

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? ($for ?? null);
    if (!isset($for)) {
        throw new InvalidArgumentException("The `$componentName` component requires either a `for` or `wire:model` attribute to be set.");
    }
@endphp

<select name="{{ $for }}" id="{{ $for }}"
    {{ $attributes->merge([
            'class' => $errors->has($for) ? 'bdr-red z-100 placeholder-red-400' : null,
        ])->except(['for']) }}>

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
