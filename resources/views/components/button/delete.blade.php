@props([ 'text' => 'Delete', 'withIcon' => false, 'iconClass' => '', 'iconOnly' => false ])

<button type="button" {{ $attributes->merge(['class' => 'btn danger']) }}>

    @if($withIcon || $iconOnly)
        <?php $withIcon = is_string($withIcon) ? $withIcon : 'trash'; ?>
        <x-gt-icon name="{{ $withIcon }}" class="icon {{ $iconClass }}" />
    @endif

    @unless($iconOnly)
        @isset($text)
            <span>{{ $text }}</span>
        @endisset
    @endunless

</button>
