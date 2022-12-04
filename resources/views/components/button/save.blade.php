@props([ 'text' => 'Save', 'icon' => false, 'iconClass' => '', 'iconOnly' => false ])

<button type="button" {{ $attributes->merge(['class' => 'btn primary']) }}>

    @if($icon || $iconOnly)
        @php
            $icon = is_string($icon) ? $icon : 'save-o';
        @endphp
        <x-dynamic-component :component="'iconit-' .$icon" class="icon {{ $iconClass }}" />
    @endif

    @unless($iconOnly)
        @isset($text)
            <span>{{ $text }}</span>
        @endisset
    @endunless

</button>
