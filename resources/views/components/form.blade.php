@props(['submit' => 'save'])

    <div {{ $attributes->merge(['class' => '']) }}>

        <form wire:submit.prevent="{{ $submit }}">

            {{ $slot }}

        </form>

        @if(isset($actions))
            {{ $actions }}
        @else
            <div class="tar">
                <button wire:click.prevent="save" class="btn primary">Save</button>
                <button wire:click.prevent="cancel" class="btn">Cancel</button>
            </div>
        @endif

    </div>
