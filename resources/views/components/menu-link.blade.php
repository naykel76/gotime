@props(['active', 'url', 'itemClass', 'newWindow', 'isParent' => false])

<a href="{{ url($url) }}"
    @class(['active'=> $active, $itemClass ])
    @if($newWindow) target="_blank" @endif
>

    {{ $slot }}

    @if($isParent)
        <x-gt-icon-caret-down class="ml-025" />
    @endif

</a>
