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

        <button wire:click.prevent="save('save_stay');" class="btn sm primary txt-upper">
            <x-gt-icon-save class="icon" /> <span>Save</span> </button>

        <button wire:click.prevent="save('save_close')" class="btn sm primary txt-upper">
            <x-gt-icon-exit class="icon" /> <span>Save and Close</span> </button>

    </div>

    <div>

        <a href="{{ route("$routePrefix.index") }}" class="btn sm txt-upper dark">
            <x-gt-icon-exit class="icon" /> <span>{{ $resource }} Table</span>
        </a>

    </div>

</div>
