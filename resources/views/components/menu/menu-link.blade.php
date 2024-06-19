@props(['active', 'url', 'itemClass', 'newWindow', 'isParent' => false])

{{-- Some menu items, especially in the `authit` user navigation dropdown, shrink due to the
inline-flex property. Adding w-full resolves this issue. This solution seems conflict-free at the
moment, but need to test further. --}}

{{-- Add the href only if a URL exists to prevent parent items redirecting to the home page. --}}
<a @if ($url) href="{{ url($url) }}" @endif @class(['active' => $active, $itemClass, 'w-full inline-flex va-b'])
    @if ($newWindow) target="_blank" @endif>

    {{ $slot }}

    @if ($isParent)
        <x-gt-icon name="chevron-down" class="wh-1 ml-025" />
    @endif
</a>
