@props(['controlOnly' => false, 'for' => null, 'placeholder' => null ])

    {{-- the slot is required to make sure the options get passed through --}}
    @if($controlOnly)
        <x-gotime::input.control-choices {{ $attributes }}>{{ $slot }}</x-gotime::input.control-choices>
    @else
        <x-gotime::input.control-group-layout>
            <x-gotime::input.control-choices {{ $attributes }}>{{ $slot }}</x-gotime::input.control-choices>
        </x-gotime::input.control-group-layout>
    @endif
