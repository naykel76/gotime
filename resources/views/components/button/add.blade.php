@props([ 'text' => 'Add', 'icon' => false, 'iconClass' => '', 'iconOnly' => false ])

<button type="button" {{ $attributes->merge(['class' => 'btn success']) }}>

    @if($icon || $iconOnly)
        @php
        $icon = is_string($icon) ? $icon : 'plus-round';
        @endphp
        <x-dynamic-component :component="'gt-icon-' .$icon" class="icon {{ $iconClass }}" />
    @endif

    @unless($iconOnly)
        @isset($text)
            <span>{{ $text }}</span>
        @endisset
    @endunless

</button>
