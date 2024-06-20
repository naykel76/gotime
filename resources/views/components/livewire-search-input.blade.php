<x-gt-input wire:model.live.debounce.500="search" for="search" {{ $attributes->merge(['placeholder' => 'Search...']) }}>
    <x-slot name="leadingAddon">
        <x-gt-icon name="search" class="opacity-05" />
    </x-slot>
</x-gt-input>
