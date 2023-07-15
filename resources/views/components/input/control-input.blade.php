@aware([ 'for' => null, 'value' => null])

    {{-- borders are managed by JTB --}}
    <x-gotime::input.control-layout>
        <input {{ $for ? "name=$for id=$for" : null }}
            {{ $attributes->class( [ 'bdr-red' => $errors->has( $for ) ] ) }}
            @if(old($for) || $value) value="{{ old($for) ? old($for) : ($value) }}" @endif
        />
    </x-gotime::input.control-layout>
