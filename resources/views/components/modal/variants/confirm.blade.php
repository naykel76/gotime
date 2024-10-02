@props(['maxWidth' => null])

<x-gt-modal.base :$maxWidth {{ $attributes }}>

    
{{-- can i override wire:click to pass id??? --}}

    {{-- @isset($title)
        <div {{ $title->attributes->class(['bx-title flex va-c space-between']) }}>
            {{ $title }}
            <x-gt-icon name="x-mark" wire:click="$toggle('showModal')" class="close sm" />
        </div>
    @endisset

    <div class="bx-content">
        {{ $slot }}
    </div>

    @isset($footer)
        <div {{ $footer->attributes->class(['bx-footer']) }}>
            {{ $footer }}
        </div>
    @endisset --}}
</x-gt-modal.base>
