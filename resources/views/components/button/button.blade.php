@props([
    'text',
    'withIcon' => false,
    'iconClass' => '',
    'iconOnly' => false
    ])

    <button type="button" {{ $attributes->merge(['class' => 'btn']) }}>

        @if($withIcon)
            <x-dynamic-component :component="'gt-icon-' .$withIcon" class="icon {{ $iconClass }}" />
        @endif

        @unless($iconOnly)
            @isset($text)
                <span>{{ $text }}</span>
            @endisset
        @endunless

    </button>
