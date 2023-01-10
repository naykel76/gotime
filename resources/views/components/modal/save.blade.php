@props(['id' => null, 'maxWidth' => null])

    <x-gt-modal :$id :$maxWidth {{ $attributes }}>
        <div class="bx-header flex space-between va-c">
            <div class="bx-title">
                {{ isset($this->editing->id) ? 'Edit' : 'Create' }} Page Section
            </div>
            <x-gt-icon-cross wire:click="$toggle('showModal')" class="close sm" />
        </div>

        <form wire:submit.prevent="save">

            {{ $slot }}

        </form>

        <div class="tar">
            <button wire:click.prevent="save()" class="btn primary">Save</button>
            <button wire:click.prevent="cancel()" class="btn">Cancel</button>
        </div>

    </x-gt-modal>
