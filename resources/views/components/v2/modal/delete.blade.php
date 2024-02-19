@props(['id' => null, 'maxWidth' => null, 'withRedirect' => false])

<x-gt-modal.base :$id :$maxWidth {{ $attributes }}>

    <div class="bx-title inline-flex va-c">
        <div class=" pxy-05 bg-rose-50 rounded-full"><x-gt-icon name="exclamation-triangle" class="txt-red" /></div>
        <span class="ml-1">Confirm Delete</span>
    </div>


    <p>Are you sure you want to delete this item? All data related to this item will be
        permanently removed. This action is final and can not be undone.</p>

    {{-- need to consider how handle deleting from a parent, as well as a child  --}}
    <div class="bx-footer tar">
        <x-gt-button wire:click="$set('actionId', false)"
            wire:loading.attr="disabled" text="Cancel" />

        @if ($withRedirect)
            <x-gt-button wire:click="delete({{ $id }}, 'delete_close')"
                text="Delete" class="danger" />
        @else
            <x-gt-button wire:click="delete({{ $id }})"
                text="Delete" class="danger" />
        @endif
    </div>

</x-gt-modal.base>
