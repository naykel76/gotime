@aware(['leadingAddon', 'trailingAddon', 'for' => null])

    {{-- the control layout manages the addons --}}
    <x-gotime::input.control-layout>
        <input x-data x-ref="datepicker" x-on:change="$dispatch('input', $el.value)"
            x-init="new Pikaday({ field: $refs.datepicker, format: 'DD-MM-YYYY' })"
            {{ $for ? "name=$for id=$for" : null }}
            {{ $attributes->class(
                [ 'bdr-red' => $errors->has( $for ),
                 'bdrr-l-0 bdr-l-0' => $leadingAddon,
                 'bdrr-r-0 bdr-r-0' => $trailingAddon, ]
                )}} />
    </x-gotime::input.control-layout>

    {{-- @if(old($for) || $value) value="{{ old($for) ? old($for) : ($value) }}" @endif --}}

    @pushOnce('head')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    @endPushOnce

    @pushOnce('scripts')
        <script src="https://unpkg.com/moment"></script>
        <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    @endPushOnce