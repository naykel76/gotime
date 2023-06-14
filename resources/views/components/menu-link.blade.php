@props(['active', 'url', 'itemClass', 'isParent' => false])

<a href="{{ url($url) }}" @class(['active'=> $active, $itemClass ])>

    {{ $slot }}

    @if($isParent)
        <x-gt-icon-caret-down class="ml-025" />
    @endif

</a>
