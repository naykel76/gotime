@props(['id', 'maxWidth'])

    @php
        $id = $id ?? md5($attributes->wire('model'));

        $maxWidth = [
        'sm' => 'maxw-400px',
        'md' => 'maxw-600px',
        'lg' => 'maxw-md',
        'xl' => 'container',
        ][$maxWidth ?? 'md'];

    @endphp

    <div
        x-data="{ show: @entangle($attributes->wire('model')) }"
        x-on:close.stop="show = false"
        x-on:keydown.escape.window="show = false"
        x-show="show"
        id="{{ $id }}" class="overlay"
        style="display: none;">

        <div x-show="show" class="bx {{ $maxWidth }} mx-auto my">
            {{ $slot }}
        </div>
    </div>
