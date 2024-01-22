@props([ 'text' => 'Save', 'withIcon' => false, 'iconClass' => '', 'iconOnly' => false ])

<button type="button" {{ $attributes->merge(['class' => 'btn primary']) }}>

    @if($withIcon || $iconOnly)
        <?php $withIcon = is_string($withIcon) ? $withIcon : 'arrow-path';?>
        <x-gt-icon name="{{ $withIcon }}" class="icon {{ $iconClass }}" />
    @endif

    @unless($iconOnly)
        @isset($text)
            <span>{{ $text }}</span>
        @endisset
    @endunless

</button>
