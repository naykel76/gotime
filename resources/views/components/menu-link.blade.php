@props(['active', 'url', 'itemClass', 'isParent' => false])

<a href="{{ url($url) }}" @class(['active'=> $active, $itemClass ])>

    {{ $slot }}

    @if($isParent)
        <x-gt-icon-down-caret class="ml-025" />
    @endif

</a>
