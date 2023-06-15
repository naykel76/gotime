<div x-data="{ show: false, message: '' }" x-show="show"
    x-on:notify.window="show = true; message = $event.detail; setTimeout(() => { show = false }, 6000)">

    <div class="success fixed pos-b pos-r mxy flex va-c space-between bx minw-300 z-top">

        <x-gt-icon-tick-round class="fs0 txt-green" />

        <div x-text="message" class="mx"></div>

        <x-gt-icon-cross class="fs0 close" @click="show = false" />

    </div>

</div>
