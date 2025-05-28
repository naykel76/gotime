{{-- NKTD: Add support for addons --}}
@props([
    'placeholder' => null,
    'options' => [],
])

{{-- this check needs to be in both the control, and component to make sure we cover both cases --}}
@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $for;
    if (!isset($for)) {
        throw new InvalidArgumentException('A `for` or `wire:model` attribute must be provided for this form control.');
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
