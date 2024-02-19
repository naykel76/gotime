{{-- THIS IS A DUPLICATE AND CAN BE REMOVED WHEN ALL THE MODALS HAVE BEEN REVISED --}}
{{-- THIS IS A DUPLICATE AND CAN BE REMOVED WHEN ALL THE MODALS HAVE BEEN REVISED --}}
{{-- THIS IS A DUPLICATE AND CAN BE REMOVED WHEN ALL THE MODALS HAVE BEEN REVISED --}}
{{-- THIS IS A DUPLICATE AND CAN BE REMOVED WHEN ALL THE MODALS HAVE BEEN REVISED --}}
{{-- THIS IS A DUPLICATE AND CAN BE REMOVED WHEN ALL THE MODALS HAVE BEEN REVISED --}}
{{-- THIS IS A DUPLICATE AND CAN BE REMOVED WHEN ALL THE MODALS HAVE BEEN REVISED --}}
{{-- THIS IS A DUPLICATE AND CAN BE REMOVED WHEN ALL THE MODALS HAVE BEEN REVISED --}}


@props(['id', 'maxWidth'])

@php
    $id = $id ?? md5($attributes->wire('model'));

    $maxWidth = [
        'sm' => 'container-sm',
        'md' => 'container-md',
        'lg' => 'container-lg',
        'xl' => 'container',
    ][$maxWidth ?? 'sm'];

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
