@props(['id' => null, 'maxWidth' => null])

<x-gt-modal :$id :$maxWidth {{ $attributes }}>

    @isset($title)
        <div class="bx-header flex space-between va-c">
            <div class="bx-title">
                {{ $title }}
            </div>
            <x-gt-icon-cross wire:click="$toggle('showModal')" class="close sm" />
        </div>
    @endisset

    <form wire:submit.prevent="save">
        {{ $form }}
    </form>

    {{-- default save and cancel buttons will be created unless overriden by the $footer slot --}}
    <div class="tar">
        @if(isset($footer))
            {{ $footer }}
        @else
            <button wire:click.prevent="save" class="btn primary">Save</button>
            <button wire:click.prevent="cancel" class="btn">Cancel</button>
        @endif
    </div>

</x-gt-modal>
