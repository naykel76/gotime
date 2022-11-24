@props(['id', 'maxWidth'])

    @php
        $maxWidth = [
        'xs' => 'maxw300',
        'sm' => 'maxw400',
        'md' => 'maxw600',
        'lg' => 'maxw800',
        'xl' => 'container',
        ][$maxWidth ?? 'md'];

    @endphp


    <div x-data="{show: @entangle($attributes->wire('model')).defer}"
        x-show="show" x-cloak
        x-on:keydown.escape.window="show = false"
        class="overlay" role="dialog" aria-modal="true">

        <div x-show="show" class="bx {{ $maxWidth }} mx-auto my">

            {{ $slot }}

        </div>

    </div>
