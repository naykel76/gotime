@once
    @push('head')
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.0.1/styles/base16/railscasts.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.0.1/highlight.min.js"></script>

        <script>
            hljs.initHighlightingOnLoad();
        </script>
    @endpush
@endonce

@parsedown($file)
