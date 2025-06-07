@props([
    'for' => null,
    'placeholder' => null,
    'options' => [],
    'componentName' => 'choices',
])

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? ($for ?? null);
    if (!isset($for)) {
        throw new InvalidArgumentException("The `$componentName` component requires either a `for` or `wire:model` attribute to be set.");
    }
@endphp

<select x-data x-init="function() { choices($el) }" multiple wire:ignore
    name="{{ $for }}" id="{{ $for }}"
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

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
        <script>
            window.choices = (element) => {
                return new Choices(element, {
                    maxItemCount: 5,
                    removeItemButton: true,
                    shouldSort: true,
                    noResultsText: 'not found...',
                    placeholder: true,
                });
            }
        </script>
    @endpush
@endonce
