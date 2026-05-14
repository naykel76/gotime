@props(['size' => '1.5'])

<svg {{ $attributes->merge(['class' => 'animate-spin wh-'.$size]) }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36">
    <circle cx="18" cy="18" r="14" fill="none" stroke="currentColor" stroke-width="3" stroke-opacity="0.25" />
    <circle cx="18" cy="18" r="14" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="butt" stroke-dasharray="22 66" />
</svg>
