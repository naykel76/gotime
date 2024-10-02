@props(['maxWidth' => null, 'title' => null])

{{-- This is intended to be an opinionated modal so don't go crazy with options --}}

<x-gt-modal.base :$maxWidth {{ $attributes }}>
    {{-- close button should always be available --}}
    <div class="bx-title flex va-c space-between">
        @isset($title)
            <div class="bx-title">
                {{ $title }}
            </div>
        @endisset
        <x-gt-icon name="x-mark" wire:click="$toggle('showModal')" class="close sm" />
    </div>

    {{ $slot }}

    @isset($footer)
        <div {{ $footer->attributes->class(['bx-footer']) }}>
            {{ $footer }}
        </div>
    @endisset
</x-gt-modal.base>
