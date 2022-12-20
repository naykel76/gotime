@props([
    'text',
    'icon' => false,
    'iconClass' => '',
    'iconOnly' => false
    ])

    <button type="button" {{ $attributes->merge(['class' => 'btn']) }}>

        @if($icon)
            <x-dynamic-component :component="'gt-icon-' .$icon" class="icon {{ $iconClass }}" />
        @endif

        @unless($iconOnly)
            @isset($text)
                <span>{{ $text }}</span>
            @endisset
        @endunless

    </button>
