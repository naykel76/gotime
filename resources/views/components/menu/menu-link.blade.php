@props(['active', 'url', 'itemClass', 'newWindow', 'isParent' => false])

{{-- Add the href only if a URL exists to prevent parent items redirecting to the home page. --}}
<a @if ($url) href="{{ url($url) }}" @endif @class(['active' => $active, $itemClass, 'inline-flex va-b'])
    @if ($newWindow) target="_blank" @endif>

    {{ $slot }}

    @if ($isParent)
        <x-gt-icon name="chevron-down" class="wh-1 ml-025" />
    @endif
</a>
