@props(['text', 'icon' => false, 'iconClass' => '' ])

<button type="button" {{ $attributes->merge(['class' => 'btn']) }}>
    @if($icon) <x-dynamic-component :component="'iconit-' .$icon"  class="icon {{ $iconClass }}" /> @endif
    <span>{{ $text }}</span>
</button>
