<div>
    @markdown($file)
</div>

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
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
        hljs.highlightAll();
    </script>
@endPushOnce
