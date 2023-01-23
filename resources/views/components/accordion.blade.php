@props(['data'])

{{-- this will not be a one size fits all but it is a good starting point! --}}

{{-- to collapse first item add ...  active: 0,  --}}


<div x-data="{ items: {{ $data }} }" class="my  space-y-05">

    <template x-for="(item, index) in items" :key="index">

        <div x-data="{
            get expanded() { return this.active === this.index },
            set expanded(value) { this.active = value ? this.index : null },
        }" role="region" class="bx pxy-1">

            <h3 {{ $attributes->class(['txt-lg'])->whereDoesntStartWith('wire:model') }}>
                <button x-on:click="expanded = !expanded" :aria-expanded="expanded" class="flex space-between w-full tal">
                    <span x-text="item.title"></span>
                    <span x-show="expanded" aria-hidden="true" class="fw-9"><span class="txt-muted txt-xl">&minus;</span></span>
                    <span x-show="!expanded" aria-hidden="true" class="fw-9"><span class="txt-muted txt-xl">&plus;</span></span>
                </button>
            </h3>

            <div x-text="item.body" x-show="expanded"></div>
        </div>

    </template>

</div>
