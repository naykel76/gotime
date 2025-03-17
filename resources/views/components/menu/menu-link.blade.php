@props([
    'text' => null,
    'active' => false,
    'newWindow' => false,
    'isParent' => false,
    'url',
])

<a {{ $attributes->merge(['class' => 'w-full inline-flex va-b']) }}
    @if ($newWindow) target="_blank" @endif
    @if ($url) href="{{ url($url) }}" @endif>

    {{ $text ?? $slot }}

    @if ($isParent)
        <x-gt-icon name="chevron-down" class="wh-1 ml-025" />
    @endif
</a>
