@props(['title' => null])

{{-- This component has some redundancy, but it's necessary for attributes to work correctly --}}

<div {{ $attributes->merge(['class' => 'bx']) }}>
    @isset($header)
        <header {{ $header->attributes->class(['bx-header']) }}>
            {{ $header }}

            @isset($title)
                <div {{ $title->attributes->class(['bx-title']) }}>{{ $title }}</div>
            @endisset
        </header>
    @else
        @isset($title)
            <div class="bx-title">{{ $title }}</div>
        @endisset
    @endisset

    {{-- Render main slot content if it exists --}}
    @if ($slot->hasActualContent())
        {{ $slot }}
    @endif

    {{-- Render content slot if provided. Note: Both the content slot and
    content attribute can be used at the same time, so this may appear redundant
    but allows for custom styling. --}}
    @isset($content)
        <div {{ $content->attributes->class(['bx-content']) }}>
            {{ $content }}
        </div>
    @endisset

    @isset($footer)
        <footer {{ $footer->attributes->class(['bx-footer']) }}>
            {{ $footer }}
        </footer>
    @endisset
</div>
