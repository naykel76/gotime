<div>
    @markdown($file)
</div>

@pushOnce('head')
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
        hljs.highlightAll();
    </script>
@endPushOnce
