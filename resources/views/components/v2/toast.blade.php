<div x-data="{ show: false, message: '' }" x-show="show"
    x-on:notify.window="
        show = true; message = $event.detail;
        setTimeout(() => { show = false }, 3000)
    "
    x-transition.out.opacity.duration.1000ms>

    <div class="bx pxy-1 fixed pos-b pos-r mxy flex va-c minw-16 z-top">
        <x-gt-icon-tick-round class="txt-green" />
        <div x-text="message" class="mx-1"></div>
        <x-gt-icon-cross class="fs0 close icon ml-auto" @click="show = false" />
    </div>
</div>
