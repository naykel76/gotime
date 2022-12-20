@props([ 'text' => 'Edit', 'icon' => false, 'iconClass' => '', 'iconOnly' => false ])

<button type="button" {{ $attributes->merge(['class' => 'btn blue']) }}>

    @if($icon || $iconOnly)
        @php
            $icon = is_string($icon) ? $icon : 'edit-o';
        @endphp
        <x-dynamic-component :component="'gt-icon-' .$icon" class="icon {{ $iconClass }}" />
    @endif

    @unless($iconOnly)
        @isset($text)
            <span>{{ $text }}</span>
        @endisset
    @endunless

</button>
