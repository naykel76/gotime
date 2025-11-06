{{-- Animated chevron that toggles between up/down based on Alpine.js 'open'
state in parent scope. Requires an Alpine component with x-data="{ open: ... }"
in a parent element. --}}
@props(['size' => 'wh-1'])

<svg class="{{ $size }}" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
    <path x-show="open" d="m6 15 6-6 6 6" />
    <path x-show="!open" d="m6 9 6 6 6-6" />
</svg>