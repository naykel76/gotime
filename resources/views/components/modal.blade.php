@props(['id', 'maxWidth'])

    @php
        $maxWidth = [
        'xs' => 'maxw300',
        'sm' => 'maxw-400',
        'md' => 'maxw-600',
        'lg' => 'maxw-800',
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
