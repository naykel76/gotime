@props(['id', 'maxWidth'])

@php
    $maxWidth = [
        'sm' => 'container-sm',
        'md' => 'container-md',
        'lg' => 'container-lg',
        'xl' => 'container-xl',
    ][$maxWidth ?? 'md'];
@endphp

<main {{ $attributes->class([$maxWidth, 'py-3']) }}>
    {!! $content !!}
</main>

@if ($toc)
    <nav>
        {!! $toc !!}
    </nav>
@endif

@pushOnce('head')
    <style>
        .heading-permalink {
            visibility: hidden;
        }

        h1:hover .heading-permalink,
        h2:hover .heading-permalink,
        h3:hover .heading-permalink,
        h4:hover .heading-permalink,
        h5:hover .heading-permalink,
        h6:hover .heading-permalink {
            visibility: visible;
        }
    </style>
@endPushOnce
