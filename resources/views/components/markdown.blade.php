{!! $renderedContent !!}

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