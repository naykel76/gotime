@props(['id' => null, 'maxWidth' => null, 'withRedirect' => false])

<x-gt-modal :$id :$maxWidth {{ $attributes }}>

    <div class="bx-title">Confirm Delete</div>

    <p>Are you sure you want to delete this item? <br> This action is final and can not be undone.</p>

    <div class="bx-footer tar">
        <button wire:click.prevent="$set('actionItemId', false)"
            wire:loading.attr="disabled" class="btn">Nevermind </button>

        @if($withRedirect)
            <button wire:click.prevent="delete({{ $id }}, 'delete_close')" class="btn danger">Delete</button>
        @else
            <button wire:click.prevent="delete({{ $id }})" class="btn danger">Delete</button>
        @endif

    </div>

</x-gt-modal>
