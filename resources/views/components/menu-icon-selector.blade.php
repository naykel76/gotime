{{-- this component is used to handle menu icons --}}

@if($withIcons && isset($item->icon_logo))
    <svg class="icon {{ $iconClass }}">
        <use href="/svg/naykel-logos.svg#{{ $item->icon_logo }}"></use>
    </svg>
@elseif($withIcons && isset($item->icon))
    <x-dynamic-component :component="'gt-icon-'.$item->icon" class="icon {{ $iconClass }}" />
@endif

