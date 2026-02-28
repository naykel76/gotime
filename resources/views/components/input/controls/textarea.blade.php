
@props([
    'for' => null,
    'value' => null,
    'componentName' => 'input control',
])

@php
    $for = getFormFieldName($attributes, $for, $componentName);
@endphp


<textarea {{ $for ? "name=$for id=$for" : null }}
    {{ $attributes->merge([
            'class' => $errors->has($for) ? 'bdr-2 bdr-red-400 placeholder-red-400' : null,
        ])->except(['for']) }}>{{ old($for, $value) }}</textarea>

