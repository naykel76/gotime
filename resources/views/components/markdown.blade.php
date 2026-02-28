<main class="markdown-content flex-1 min-w-0">
    <div class="container-md py-3">
        {!! $content !!}
    </div>
</main>

@if ($toc)
    <nav class="right-sidebar fs-0">
        <h2 class="txt-1 font-semibold txt-gray-900 mb-05">On This Page</h2>
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
