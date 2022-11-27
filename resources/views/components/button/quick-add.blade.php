@props(['buttonText'])

<button {{ $attributes }} class="btn primary">
    <x-iconit-lightning class="icon" /> <span>{{ $buttonText ?? 'Quick Add' }}</span></button>
