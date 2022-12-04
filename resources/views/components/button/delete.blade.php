@props(['withIcon' => false])

@props([ 'text' => 'Delete', 'icon' => false, 'iconClass' => '', 'iconOnly' => false ])

<button type="button" {{ $attributes->merge(['class' => 'btn danger']) }}>

    @if($icon || $iconOnly)
        @php
            $icon = is_string($icon) ? $icon : 'trash';
        @endphp
        <x-dynamic-component :component="'iconit-' .$icon" class="icon {{ $iconClass }}" />
    @endif

    @unless($iconOnly)
        @isset($text)
            <span>{{ $text }}</span>
        @endisset
    @endunless

</button>
