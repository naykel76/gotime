@props(['icon', 'text'])

    <x-dynamic-component :component="'gt-icon-' .$icon" {{ $attributes->merge(['class' => 'icon']) }} />

    @isset($text)
        <span>{{ $text }}</span>
    @endisset
