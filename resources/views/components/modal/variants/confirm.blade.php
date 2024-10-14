@props(['selectedItemId' => null, 'maxWidth' => null])

<x-gt-modal.base id="$selectedItemId" :$maxWidth {{ $attributes }}>
    <div class="bx-title inline-flex va-c">
        <div class="pxy-05 bg-yellow-50 rounded-full">
            <x-gt-icon name="exclamation-triangle" class="txt-yellow" />
        </div>
        <span class="ml-1">Confirm Action</span>
    </div>

    <div class="bx-content">
        {{ $slot }}
    </div>

    <div class="bx-footer tar">
        <x-gt-button wire:click="$set('selectedItemId', false)" wire:loading.attr="disabled" text="Nevermind" />
        {{-- force the action to be passed through to make the modal more flexible --}}
        {{ $action }}
    </div>
</x-gt-modal.base>