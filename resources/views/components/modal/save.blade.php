@props(['id' => null, 'maxWidth' => null])

{{-- <x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}> --}}

<x-gt-modal>

    {{-- <div class="flex va-c ha-c pxy">

        <div class="bx container {{ $maxWidth }}">

            @isset($title)

                <div {{ $title->attributes->class(['bx-title flex va-c space-between']) }}>

                    {{ $title }}

                    <x-gotime-icon wire:click="$toggle('showModal')" icon="close" class="close sm" />

                </div>

            @endisset

        </div>

    </div> --}}

</x-gt-modal>