@props([
    'text',
    'withIcon' => false,
    'iconClass' => '',
    'iconOnly' => false,
    'noOpinion' => false
    ])


{{-- THIS WILL BE DEPRECATED IN PLACE OF V2 --}}
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
