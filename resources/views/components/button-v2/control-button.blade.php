@aware([ 'withIcon' => false, 'iconClass' => '', 'iconOnly' => false ])

{{--
    if the text is empty, and the icon is set, then display the icon only. I
    am not convinced this is the best way to handle of if I should use a
    dedicated `iconOnly` flag.
 --}}

<button {{ $attributes->merge(['class' => 'btn']) }}>

    @if($withIcon)
        <x-dynamic-component :component="'gt-icon-' .$withIcon" class="icon {{ $iconClass }}" />
    @endif

    @if($text != '')
        <span>{{ $text }}</span>
    @endif

</button>
