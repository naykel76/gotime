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

            <x-gt-button-save wire:click.prevent="save('save_stay');" withIcon text="SAVE" />
            <x-gt-button-save wire:click.prevent="save('save_new');" withIcon text="NEW" class="purple" />
            <x-gt-button-save wire:click.prevent="save('save_close');" withIcon text="CLOSE" class="purple" />
            <x-gt-button-delete wire:click.prevent="setActionItemId({{ $editing->id }})" withIcon iconOnly />

        </div>

        <div>

            @if(Route::has("$routePrefix.index"))

                <a href="{{ route("$routePrefix.index") }}" class="btn txt-upper dark">
                    <x-gt-icon-exit class="icon" /> <span>{{ $resource }} Table</span>
                </a>

            @else

                <button class="btn dark" disabled>Not Available</button>

            @endif

        </div>

    </div>
