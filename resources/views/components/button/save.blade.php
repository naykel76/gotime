@props([ 'text' => 'Save', 'withIcon' => false, 'iconClass' => '', 'iconOnly' => false ])

<button type="button" {{ $attributes->merge(['class' => 'btn']) }}>

    @if($withIcon || $iconOnly)
        <?php $withIcon = is_string($withIcon) ? $withIcon : 'arrow-path';?>
        <x-gt-icon name="{{ $withIcon }}" class="wh-1 {{ $iconClass }}" />
    @endif

    @unless($iconOnly)
        @isset($text)
            <span class="ml-05">{{ $text }}</span>
        @endisset
    @endunless

</button>
