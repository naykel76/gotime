@props(['id' => null, 'maxWidth' => null])

    <x-gt-modal :$id :$maxWidth {{ $attributes }}>

        @isset($title)
            <div class="bx-header flex space-between va-c">
                <div class="bx-title">
                    {{ $title }}
                </div>
                <x-gt-icon name="x-mark" wire:click="$toggle('showModal')" class="close sm" />
            </div>
        @endisset

        <form wire:submit="save">
            {{ $form }}
        </form>

        {{-- default save and cancel buttons will be created unless overridden by the $footer slot --}}
        @if(isset($footer))
            {{ $footer }}
        @else
            <div class="tar">
                <button wire:click.prevent="save" class="btn primary">Save</button>
                <button wire:click.prevent="cancel" class="btn">Cancel</button>
            </div>
        @endif

    </x-gt-modal>
