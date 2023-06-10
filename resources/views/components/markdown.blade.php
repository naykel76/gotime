
<div {{ $attributes }}>
    {!! \Illuminate\Support\Str::of($slot)->markdown() !!}
</div>

@pushOnce('head')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/github-dark-dimmed.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
    <script> hljs.highlightAll(); </script>
@endPushOnce
