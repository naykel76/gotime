@props(['action' => null])

{{-- This button is tightly coupled to the save method for the CRUD system --}}
{{-- <x-gt-button.base wire:click="save('{{ $action }}')" class="btn primary px-075" disabled wire:dirty.remove.attr="disabled"> --}}
<x-gt-button.base wire:click="save('{{ $action }}')" class="btn primary px-075">
    <x-gt-icon name="disk" class="wh-1" />
    <span class="ml-05">Save</span>
</x-gt-button.base>
