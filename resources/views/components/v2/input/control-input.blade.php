@aware(['for', 'value' => null])

@unless (isset($for))
    @php
        throw new Exception('The `$for` variable is not set on the form control.');
    @endphp
@endunless

<x-gotime::v2.input.partials.control-only>
    <input {{ $for ? "name=$for id=$for" : null }}
        {{ $attributes->class(['bdr-red z-100' => $errors->has($for)]) }}
        @if (old($for) || $value) value="{{ old($for) ? old($for) : $value }}" @endif />
</x-gotime::v2.input.partials.control-only>
