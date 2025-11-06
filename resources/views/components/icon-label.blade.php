{{-- Display text with an optional icon prefix. 
Supports both label prop and slot for content. --}}
@props(['label' => null, 'icon' => null])

@if ($icon)
    <span class="inline-flex items-center gap-05">
        <x-gt-icon :name="$icon" />
        {{ $label ?? $slot }}
    </span>
@else
    {{ $label ?? $slot }}
@endif
