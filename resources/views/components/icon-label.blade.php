{{-- 
    Text with optional icon prefix.
    
    Usage:
    - Pass text via 'label' prop OR slot content
    - Add 'icon' prop to display an icon before the text
    - Add 'iconType' prop to specify custom icon type (e.g., 'brands')
--}}

@props(['label' => null, 'icon' => null, 'iconType' => null])

@if ($icon)
    <span {{ $attributes->merge(['class' => 'inline-flex items-center gap-05']) }}>
        <x-gt-icon :name="$icon" :type="$iconType" />
        {{ $label ?? $slot }}
    </span>
@else
    {{ $label ?? $slot }}
@endif
