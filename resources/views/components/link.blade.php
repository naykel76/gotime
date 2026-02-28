@props(['text' => null])

<a href="{{ url($url) }}" {{ $attributes->merge() }}>
    {{ $text ?? $slot }}
</a>
