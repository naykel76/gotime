@props(['buttonText', ])

<button {{ $attributes }} class="btn primary">
    <x-iconit.save-o class="icon" /> <span>{{ $buttonText ?? 'Save' }}</span></button>
