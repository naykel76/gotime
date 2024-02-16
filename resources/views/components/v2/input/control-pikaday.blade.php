@aware(['leadingAddon', 'trailingAddon', 'for' => null])

<x-gotime::v2.input.partials.control-only>

    <input x-data x-ref="datepicker"
        x-init="new Pikaday({
            field: $refs.datepicker,
            format: 'DD-MM-YYYY'
        })"
        x-on:change="$dispatch('input', $el.value)"
        {{ $for ? "name=$for id=$for" : null }}
        {{ $attributes->class([
            'bdr-red' => $errors->has($for),
            'bdrr-l-0 bdr-l-0' => $leadingAddon,
            'bdrr-r-0 bdr-r-0' => $trailingAddon,
        ]) }} />

</x-gotime::v2.input.partials.control-only>

@pushOnce('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
@endPushOnce

@pushOnce('scripts')
    <script src="https://unpkg.com/moment"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
@endPushOnce


