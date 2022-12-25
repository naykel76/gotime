{{-- ------------------------------------------------------------------------
| -- $routePrefix, is the route name by convention, without the action
| -- this form has access to the livewire $editing property
------------------------------------------------------------------------ --}}

@aware(['routePrefix', 'editing'])

    @php
        $resource = dotLastSegment($routePrefix)
    @endphp

    <div id="actions-toolbar" class="pxy-05 light my flex space-between">

        <div>

            <button wire:click.prevent="save('save_stay');" class="btn primary txt-upper">
                <x-gt-icon-save class="icon" /> <span>Save</span> </button>

            <button wire:click.prevent="save('save_close')" class="btn primary txt-upper">
                <x-gt-icon-exit class="icon" /> <span>Save and Close</span> </button>

            <x-gt-button-delete wire:click.prevent="setConfirmAction({{ $editing->id }})" icon />

        </div>

        <div>

            <a href="{{ route("$routePrefix.index") }}" class="btn txt-upper dark">
                <x-gt-icon-exit class="icon" /> <span>{{ $resource }} Table</span>
            </a>

        </div>

    </div>

    {{-- <x-gt-modal.confirmation wire:model="confirmingActionId">

        <x-slot name="title">
            Delete Item
        </x-slot>

        <p> Are you sure you want to delete this item? </p>

        <x-slot name="footer">
            <button wire:click.prevent="$set('confirmingActionId', false)"
                wire:loading.attr="disabled" class="btn">Nevermind </button>
            <button wire:click.prevent="delete({{ $confirmingActionId }})"
                class="btn danger">Delete</button>
        </x-slot>

    </x-gt-modal.confirmation> --}}
