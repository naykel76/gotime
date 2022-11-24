@props(['buttonText'])

<button {{ $attributes }} class="btn primary">
    <x-iconit.plus-round class="icon" /> <span>{{ $buttonText ?? 'add' }}</span></button>
