@props([ 'text' => 'Add', 'withIcon' => false, 'iconClass' => '', 'iconOnly' => false ])

<button type="button" {{ $attributes->merge(['class' => 'btn success']) }}>

    @if($withIcon || $iconOnly)
        <?php $withIcon = is_string($withIcon) ? $withIcon : 'plus-circle'; ?>
        <x-gt-icon name="{{ $withIcon }}" class="icon {{ $iconClass }}" />
    @endif

    @unless($iconOnly)
        @isset($text)
            <span>{{ $text }}</span>
        @endisset
    @endunless

</button>
