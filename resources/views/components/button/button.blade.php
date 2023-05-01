@props([
    'text',
    'withIcon' => false,
    'iconClass' => '',
    'iconOnly' => false,
    'noOpinion' => false
    ])

<button type="button" {{ $noOpinion ? $attributes : $attributes->merge(['class' => 'btn']) }}>

    @if($withIcon)
        <x-dynamic-component :component="'gt-icon-' .$withIcon" class="icon {{ $iconClass }}" />
    @endif

    @unless($iconOnly)
        @isset($text)
            <span>{{ $text }}</span>
        @endisset
    @endunless

</button>
