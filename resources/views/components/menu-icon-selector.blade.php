{{-- this component is used to handle menu icons --}}

@if ($withIcons && isset($item->icon_logo))
    <svg class="{{ $iconClass }}">
        <use href="/svg/naykel-logos.svg#{{ $item->icon_logo }}"></use>
    </svg>
@elseif($withIcons && isset($item->icon))
    <x-gt-icon name="{{ $item->icon }}" type="outline" class="mr-075 icon" />
@endif
