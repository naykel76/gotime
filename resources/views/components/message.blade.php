<div x-data="{ show: false, message: '' }" x-show="show" x-cloak
    x-on:msg.window="show = true; message = $event.detail; setTimeout(() => { show = false }, 3000)"
    class="fixed pos-t z-1000 pxy success-light">

    <div class="flex items-start">

        <div class="flex fg1">

            <x-gt-icon-tick-round-o class="txt-green" />

            <p x-text="message" class="ml-1.5 txt-sm"></p>

        </div>

        <div>
            <x-gt-icon-cross class="close" @click="show = false" />
        </div>
    </div>

</div>
