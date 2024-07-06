@props([ 'controlOnly' => false, 'placeholder' => null, 'options' => [], ])
{{-- do not add `for` in the props to let this do its job --}}
@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $attributes->get('for');
    if (!isset($for)) {
        throw new InvalidArgumentException('This form control must have at least one of the following attributes specified: `wire:model` or `for`. ');
    }
@endphp

@if ($controlOnly)
    <x-gotime::input.control-select {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
        {{ $slot }}
    </x-gotime::input.control-select>
@else
    @if (empty($options))
        <x-gotime::input.partials.control-group>
            <x-gotime::input.control-select {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
                {{ $slot }}
            </x-gotime::input.control-select>
        </x-gotime::input.partials.control-group>
    @else
        <x-gotime::input.partials.control-group>
            <x-gotime::input.control-select {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
                @foreach ($options as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </x-gotime::input.control-select>
        </x-gotime::input.partials.control-group>
    @endif
@endif