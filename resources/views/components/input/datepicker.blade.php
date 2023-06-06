@aware([
    'controlOnly' => false,
    'for' => null,
    'value' => null,
    'label' => null,
    'helpText' => null,
    'rowClass' => null,
    'inline' => false,
    'req' => false,
    ])

    @if($controlOnly)
        <input x-data x-ref="datepicker" x-on:change="$dispatch('input', $el.value)"
            x-init="new Pikaday({ field: $refs.datepicker, format: 'DD-MM-YYYY' })"
            {{ $for ? "name=$for id=$for" : null }}
            {{ $attributes->class(['bdr-red' => $errors->has( $for )]) }} />
    @else

        <x-gotime::input.control-group-layout>
            <input x-data x-ref="datepicker" x-on:change="$dispatch('input', $el.value)"
                x-init="new Pikaday({ field: $refs.datepicker, format: 'DD-MM-YYYY' })"
                {{ $for ? "name=$for id=$for" : null }}
                {{ $attributes->class(['bdr-red' => $errors->has( $for )]) }} />
        </x-gotime::input.control-group-layout>

    @endif

    @pushOnce('head')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    @endPushOnce

    @pushOnce('scripts')
        <script src="https://unpkg.com/moment"></script>
        <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    @endPushOnce
