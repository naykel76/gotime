@props(['buttonText'])

<button {{ $attributes }} class="btn primary">
    <x-gt-icon-lightning class="icon" /> <span>{{ $buttonText ?? 'Quick Add' }}</span></button>
