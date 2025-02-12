@aware(['for', 'value' => null])

@php
    $defaultFormat = config('gotime.date_format');
    // Determine the date format based on the default Carbon format specified in the config file
    $dateFormat = config('gotime.date_format_mappings')[$defaultFormat]['pikaday'];
@endphp

<x-gotime::input.partials.control-with-addons>

    <input x-data x-ref="datepicker"
        x-init="new Pikaday({ field: $refs.datepicker, format: '{{ $dateFormat }}' })"
        x-on:change="$dispatch('input', $el.value)"
        {{ $for ? "name=$for id=$for" : null }}
        {{ $attributes->class([
                'bdr-red z-100 placeholder-red-400' => $errors->has($for),
            ])->except(['for']) }}
        @if (old($for) || $value) value="{{ old($for) ? old($for) : $value }}" @endif />

</x-gotime::input.partials.control-with-addons>

@pushOnce('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
@endPushOnce

@pushOnce('scripts')
    <script src="https://unpkg.com/moment"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
@endPushOnce
