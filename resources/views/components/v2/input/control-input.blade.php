@aware([ 'for' => null, 'value' => null])

<x-gotime::v2.input.layout-control-only>
    <input {{ $for ? "name=$for id=$for" : null }}
        {{ $attributes->class( [ 'bdr-red z-100' => $errors->has( $for ) ] ) }}
        @if(old($for) || $value) value="{{ old($for) ? old($for) : ($value) }}" @endif
    />
</x-gotime::v2.input.layout-control-only>
