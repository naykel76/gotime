@props(['controlOnly' => false, 'for' => null, 'placeholder' => null ])

    {{-- the slot is required to make sure the options get passed through --}}
    @if($controlOnly)
        <x-gotime::input.control-choices {{ $attributes }}>{{ $slot }}</x-gotime::input.control-choices>
    @else
        <x-gotime::v2.input.layout-control-group>
            <x-gotime::input.control-choices {{ $attributes }}>{{ $slot }}</x-gotime::input.control-choices>
        </x-gotime::v2.input.layout-control-group>
    @endif
