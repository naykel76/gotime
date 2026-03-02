@props([
    'for' => null,
    'value' => null,
    'label' => null,
    'tooltip' => false,
    'rowClass' => null,
    'req' => false,
    'componentName' => 'date-picker',
])

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? ($for ?? null);
    if (!isset($for)) {
        throw new InvalidArgumentException("The `$componentName` component requires either a `for` or `wire:model` attribute to be set.");
    }

    $defaultFormat = config('gotime.date_format');
    // Determine the date format based on the default Carbon format specified in the config file
    $dateFormat = config('gotime.date_format_mappings')[$defaultFormat]['flatpickr'];
@endphp

<x-gotime::input.partials.form-row {{ $attributes->merge(['class' => $rowClass]) }}>

    @if (isset($label))
        <label for="{{ $for }}" {{ $attributes }}>
            {{ Str::of($label)->headline() }}
            @if ($req)
                <span class='txt-red'>*</span>
            @endif
        </label>
    @endif

    <div class="flex-col w-full my-0">

        <x-gotime::input.partials.control-with-addons>

            <x-slot:trailingAddon>
                <x-gt-icon name="calendar" />
            </x-slot:trailingAddon>

            <input value="{{ $value }}" type="text"
                x-data
                x-ref="datePickerInputX"
                x-init="$nextTick(() => {
                    if (typeof flatpickr !== 'undefined') {
                        flatpickr($refs.datePickerInputX, {
                            dateFormat: '{{ $dateFormat }}',
                        });
                    }
                })"
                {{ $attributes->class(['bdr-red z-higher placeholder-red-400' => $errors->has($for), 'w-full']) }}>


        </x-gotime::input.partials.control-with-addons>

        @error($for)
            <p class="mt-025 txt-xs txt-red-600" role="alert">
                {{ $message }}
            </p>
        @enderror

    </div>

</x-gotime::input.partials.form-row>

@pushOnce('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpushOnce

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpushOnce
