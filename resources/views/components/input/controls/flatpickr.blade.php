@aware(['for', 'value' => null])

@php
    $defaultFormat = config('gotime.date_format');
    // Determine the date format based on the default Carbon format specified in the config file
    $dateFormat = config('gotime.date_format_mappings')[$defaultFormat]['flatpickr'];
@endphp

<x-gotime::v2.input.partials.control-with-addons>

    <input x-data x-ref="datePickerInput"
        x-init="flatpickr($refs.datePickerInput, {
            dateFormat: '{{ $dateFormat }}',
        })"
        {{ $attributes->class([
                'bdr-red z-100 placeholder-red-400' => $errors->has($for),
                'w-full'
            ])->except(['for']) }}
        type="text">

</x-gotime::v2.input.partials.control-with-addons>

@pushOnce('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpushOnce

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpushOnce
