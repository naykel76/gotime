{{-- ------------------------------------------------------------------------
| -- $routePrefix, is the route name by convention, without the action
| -- this form has access to the livewire $editing property
------------------------------------------------------------------------ --}}

@aware(['routePrefix', 'editing', 'previewRoute'])

    <div id="actions-toolbar" class="pxy-05 light my flex space-between">

        <div>
            <x-gt-button-save wire:click.prevent="save" withIcon text="SAVE" />
            <x-gt-button-save wire:click.prevent="save('save_new');" withIcon text="NEW" />
            <x-gt-button-save wire:click.prevent="save('save_close');" withIcon text="CLOSE" />

            @isset($editing->id )
                {{-- For more flexibility do not add the delete modal here.
                NOTE: if the delete is not working as expected, make sure you
                have included 'withRedirect' attribute on the modal --}}
                <x-gt-button-delete wire:click.prevent="setActionItemId({{ $editing->id }})" withIcon iconOnly />

                @isset($previewRoute)
                    <a href="{{ route($previewRoute, $editing->slug) }}" class="btn warning" target="_blank">
                        <x-gt-icon-eyes-1 class="icon" />
                    </a>
                @endisset
            @endisset

        </div>

        <div>
            @if(Route::has("$routePrefix.index"))
                <a href="{{ route("$routePrefix.index") }}" class="btn txt-upper dark">
                    <x-gt-icon name="exit" class="icon" /> <span>{{ dotLastSegment($routePrefix) }} Table</span>
                </a>
            @endif
        </div>

    </div>
