@aware([ 'withIcon' => false, 'iconClass' => '', 'iconOnly' => false ])

{{-- if the text is empty, and the icon is set, then display the icon only. I am
not convinced this is the best way to handle. Time will tell! --}}

<button {{ $attributes->merge(['class' => 'btn']) }}>

    @if($withIcon)
        <x-gt-icon name="{{ $withIcon }}" class="icon {{ $iconClass }}" />
    @endif

    @if($text != '' || $slot->isNotEmpty())

        {{-- Use the slot if available, instead of text. Avoid wrapping this in
        a span or other element for easier styling.  --}}
        {{ $slot->isNotEmpty() ? $slot : ($text != '' ? $text : '') }}
    @endif

</button>
