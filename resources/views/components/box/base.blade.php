@props(['title' => null])

{{-- the redundancy in this component is annoying but it's 
    the easiest way to get the attributes to play nice --}}

<div {{ $attributes->merge(['class' => 'bx']) }}>
    {{-- If a header is provided, render it --}}
    @isset($header)
        <header {{ $header->attributes->class(['bx-header']) }}>
            {{ $header }}

            {{-- If a title is provided within the header, render it --}}
            @isset($title)
                <div {{ $title->attributes->class(['bx-title']) }}>{{ $title }}</div>
            @endisset
        </header>
    @else
        {{-- If no header is provided, but a title attribute exists, render it --}}
        @isset($title)
            <div class="bx-title">{{ $title }}</div>
        @endisset
    @endisset

    {{-- Check if there is any content in the main slot and render it --}}
    @if ($slot->hasActualContent())
        <div class="bx-content">
            {{ $slot }}
        </div>
    @endif

    {{-- If the content slot is used, render it.
        This may seem redundant, but it's the easiest way to add styles to the content.
        Be careful because it is possible to use both the content slot and the content attribute --}}
    @isset($content)
        <div {{ $content->attributes->class(['bx-content']) }}>
            {{ $content }}
        </div>
    @endisset

    {{-- If footer is provided, render it within the footer container --}}
    @isset($footer)
        <footer {{ $footer->attributes->class(['bx-footer']) }}>
            {{ $footer }}
        </footer>
    @endisset
</div>
