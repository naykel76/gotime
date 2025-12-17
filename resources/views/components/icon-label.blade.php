{{-- 
    Text with optional icon prefix.
    
    Usage:
    - Pass text via 'label' prop OR slot content
    - Add 'icon' prop to display an icon before the text
--}}

@props(['label' => null, 'icon' => null])

@if ($icon)
    <span {{ $attributes->merge(['class' => 'inline-flex items-center gap-05']) }}>
        <x-gt-icon :name="$icon" />
        {{ $label ?? $slot }}
    </span>
@else
    {{ $label ?? $slot }}
@endif
