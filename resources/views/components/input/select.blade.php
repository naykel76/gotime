@props([ 'controlOnly' => false, 'placeholder' => null, 'options' => [], ])
{{-- do not add `for` in the props to let this do its job --}}
@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $for;
    if (!isset($for)) {
        throw new InvalidArgumentException('The form control requires either a `for` or `wire:model` attribute to be specified.');
    }
@endphp

@if ($controlOnly)
    <x-gotime::input.control-select {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
        {{ $slot }}
    </x-gotime::input.control-select>
@else
    @if (empty($options))
        <x-gotime::v2.input.partials.control-group :$for>
            <x-gotime::input.control-select {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
                {{ $slot }}
            </x-gotime::input.control-select>
        </x-gotime::v2.input.partials.control-group>
    @else
        <x-gotime::v2.input.partials.control-group :$for>
            <x-gotime::input.control-select {{ $attributes->except(['label', 'help-text', 'rowClass']) }}>
                @foreach ($options as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </x-gotime::input.control-select>
        </x-gotime::v2.input.partials.control-group>
    @endif
@endif