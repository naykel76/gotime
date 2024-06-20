{{-- this component is used to handle menu icons --}}
@if ($withIcons && isset($icon))
    <x-gt-icon name="{{ $icon }}" type="outline" class="mr-075 icon" />
@endif
