{{-- @props(['maxWidth' => null, 'title' => null]) --}}

@props(['selectedItemId' => null, 'maxWidth' => null, 'withRedirect' => false])

{{-- 

Don't go crazy with options here, it is intended to be an opinionated!

'selectedItemId' is the ID of the item to be deleted. It is named this way to be
consistent with the trait and make it easier to use. For example:

<x-gt-modal.delete wire:model="selectedItemId" :$selectedItemId" />

--}}

<x-gt-modal.base id="$selectedItemId" :$maxWidth {{ $attributes }}>

    <div class="bx-title inline-flex va-c">
        <div class=" pxy-05 bg-rose-50 rounded-full">
            <x-gt-icon name="exclamation-triangle" class="txt-red" />
        </div>
        <span class="ml-1">Confirm Delete</span>
    </div>

    <p>Are you sure you want to delete this item? All data related to this item
    will be permanently removed. This action is final and can not be undone.</p>

    {{ $slot }}

    <div class="bx-footer tar">
        <x-gt-button wire:click="$set('selectedItemId', false)" wire:loading.attr="disabled" text="Nevermind" />
        @if ($withRedirect)
            <x-gt-button wire:click="delete({{ $selectedItemId }}, 'delete_close')" text="Delete" class="danger" />
        @else
            <x-gt-button wire:click="delete({{ $selectedItemId }})" text="Delete" class="danger" />
        @endif
    </div>

</x-gt-modal.base>
