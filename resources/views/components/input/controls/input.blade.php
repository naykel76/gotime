@aware(['for', 'value' => null])

<x-gotime::input.partials.control-with-addons>
    <input {{ $for ? "name=$for id=$for" : null }}
        {{ $attributes->class([
                'bdr-red z-100 placeholder-red-400' => $errors->has($for),
            ])->except(['for']) }}
        @if (old($for) || $value) value="{{ old($for) ? old($for) : $value }}" @endif
        autocomplete="{{ $attributes->get('autocomplete', 'off') }}"
        autocorrect="{{ $attributes->get('autocorrect', 'off') }}" />
</x-gotime::input.partials.control-with-addons>
