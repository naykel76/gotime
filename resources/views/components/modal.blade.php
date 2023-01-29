@props(['id', 'maxWidth'])

    @php
        $id = $id ?? md5($attributes->wire('model'));

        $maxWidth = [
        'xs' => 'maxw300',
        'sm' => 'maxw-400',
        'md' => 'maxw-600',
        'lg' => 'maxw-800',
        'xl' => 'container',
        ][$maxWidth ?? 'md'];

    @endphp

    <div
        x-data="{ show: @entangle($attributes->wire('model')).defer }"
        x-on:close.stop="show = false"
        x-on:keydown.escape.window="show = false"
        x-show="show"
        id="{{ $id }}" class="overlay"
        style="display: none;">

        <div x-show="show" class="bx {{ $maxWidth }} mx-auto my">
            {{ $slot }}
        </div>
    </div>
