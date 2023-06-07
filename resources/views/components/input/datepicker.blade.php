@props([
    'for' => null,
    // 'controlOnly' => false,
    // 'value' => null,
    // 'label' => null,
    // 'helpText' => null,
    // 'rowClass' => null,
    // 'inline' => false,
    // 'req' => false,
    ])

    @props(['controlOnly' => false])

        @if($controlOnly)

            {{-- the control gets injected into the control layout, where addons are managed --}}
            {{-- control only can still include icons, (no errors) --}}
            <x-gotime::input.control-datepicker {{ $attributes }} />
        @else
            <x-gotime::input.control-group-layout>
                <x-gotime::input.control-datepicker {{ $attributes }} />
            </x-gotime::input.control-group-layout>
        @endif
