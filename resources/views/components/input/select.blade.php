@props(['controlOnly' => false, 'for' => null, 'placeholder' => null ])

    @if($controlOnly)
        <select {{ $for ? "name=$for id=$for" : null }} {{ $attributes->class(['bdr-red' => $errors->has( $for )]) }}>
            @if($placeholder)
                <option disabled selected value="">{{ $placeholder }}</option>
            @endif
            {{ $slot }}
        </select>
    @else
        <x-gotime::input.control-group-layout>
            <select {{ $for ? "name=$for id=$for" : null }} {{ $attributes->class(['bdr-red' => $errors->has( $for )]) }}>
                @if($placeholder)
                    <option disabled selected value="">{{ $placeholder }}</option>
                @endif
                {{ $slot }}
            </select>
        </x-gotime::input.control-group-layout>
    @endif
