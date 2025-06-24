@props([
    'for' => null,
    'options' => [],
    'placeholder' => 'Select an option',
])

@php
    $model = $attributes->whereStartsWith('wire:model')->first();
    if (!isset($model)) {
        throw new InvalidArgumentException('The wire:model attribute must be specified for the editor control.');
    }
@endphp

<div wire:ignore x-data="slimSelect($wire.entangle('{{ $model }}'))">
    <select {{ $attributes }} x-ref="selectElement">
        @foreach ($options as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>
</div>

<script>
    window.addEventListener('alpine:init', function() {
        Alpine.data('slimSelect', (model) => ({
            values: model,
            init() {
                const SLIM = new SlimSelect({
                    select: this.$refs.selectElement,
                    settings: {},
                    events: {
                        afterChange: () => {
                            const values = Array.from(this.$refs.selectElement.selectedOptions).map(o => o.value)
                            this.$wire.set('{{ $model }}', values)
                        }
                    }
                });
                this.$watch('values', (newVal) => {
                    SLIM.setSelected(newVal);
                });
                SLIM.setSelected(this.values);
            }
        }));
    });
</script>

@pushOnce('styles')
    <link href="https://cdn.jsdelivr.net/npm/slim-select@2.8.1/dist/slimselect.min.css" rel="stylesheet" />
@endPushOnce

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/slim-select@2.8.1/dist/slimselect.min.js"></script>
@endPushOnce



{{-- settings: {
    // Below are a list of optional fields
    // their values are the defaults
    disabled: false,
    alwaysOpen: false,
    showSearch: true,
    focusSearch: true,
    searchPlaceholder: 'Search',
    searchText: 'No Results',
    searchingText: 'Searching...',
    searchHighlight: false,
    closeOnSelect: true,
    contentLocation: document.body,
    contentPosition: 'absolute',
    openPosition: 'auto', // options: auto, up, down
    placeholderText: 'Select Value',
    allowDeselect: false,
    hideSelected: false,
    showOptionTooltips: false,
    minSelected: 0,
    maxSelected: 1000,
    timeoutDelay: 200,
    maxValuesShown: 20,
    maxValuesMessage: '{number} selected',
    addableText: 'Press "Enter" to add {value}',
  }, --}}
