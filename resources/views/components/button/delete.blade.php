@props(['withIcon' => false])

<button {{ $attributes->merge(['class' => 'btn danger']) }}>
    @if($withIcon) <x-iconit-trash class="icon" /> @endif
        <span>Delete</span>
</button>
