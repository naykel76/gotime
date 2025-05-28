{{-- At this stage I do not see the point of creating a separate component for the inline 
editor. Just use the control and build from scratch instead --}}

@php
    $for = $attributes->whereStartsWith('wire:model')->first() ?? $attributes->get('for');
    if (!isset($for)) {
        throw new InvalidArgumentException('The wire:model attribute must be specified for the editor control.');
    }
@endphp

<x-gotime::v2.input.partials.control-group>
    <x-gotime::v2.input.controls.ckeditor {{ $attributes->except(['label', 'help-text', 'rowClass']) }} />
</x-gotime::v2.input.partials.control-group>
