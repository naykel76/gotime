<?php

namespace Naykel\Gotime\Components;

use Closure;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\View\Component;

class Markdown extends Component
{
    /**
     * Processed HTML content (without TOC)
     */
    protected string $content;

    /**
     * Extracted table of contents HTML
     */
    protected ?string $toc = null;

    public function __construct(
        protected string $path = '',
        protected bool $absolute = false,
        protected bool $withToc = false,
        protected string $layout = 'gotime::components.markdown',
    ) {}

    /**
     * Resolve markdown source from file path or slot content.
     */
    protected function getMarkdownContent(HtmlString|string|null $slot = null): string
    {
        if ($this->path === '') {
            return trim((string) $slot);
        }

        $filePath = $this->absolute
            ? $this->path . '.md'
            : resource_path('views/' . $this->path) . '.md';

        return getFile($filePath);
    }

    /**
     * Convert markdown to HTML and separate TOC from content.
     */
    protected function processMarkdown(string $markdown): void
    {
        $rendered = $this->renderMarkdownToHtml($markdown);

        $this->toc = $this->extractToc($rendered);
        $this->content = $this->removeTocFromHtml($rendered);
    }

    /**
     * Render markdown content to HTML using Blade's markdown directive.
     */
    protected function renderMarkdownToHtml(string $markdown): string
    {
        return Blade::render('@markdown($file)', ['file' => $markdown]);
    }

    /**
     * Remove the TOC from the HTML content if it exists.
     */
    protected function removeTocFromHtml(string $html): string
    {
        if ($this->toc === null) {
            return $html;
        }

        return str_replace($this->toc, '', $html);
    }

    /**
     * Extract the table-of-contents element: only the new format (<ul class="toc">) is supported.
     */
    protected function extractToc(string $html): ?string
    {
        // Only support new format: <ul class="toc">
        return $this->extractNestedTag($html, 'ul', 'class="[^\"]*toc[^\"]*"');
    }

    /**
     * Extract a nested HTML tag with specific attribute pattern
     */
    protected function extractNestedTag(string $html, string $tag, string $attrPattern): ?string
    {
        // Find opening tag with specified attribute
        $pattern = '/<' . $tag . '[^>]*' . $attrPattern . '[^>]*>/';

        if (! preg_match($pattern, $html, $match, PREG_OFFSET_CAPTURE)) {
            return null;
        }

        $start = $match[0][1];
        $pos = $start + strlen($match[0][0]);
        $depth = 1;

        // Count nested tags to find the matching closing tag
        $openPattern = '<' . $tag;
        $closePattern = '</' . $tag . '>';

        while ($pos < strlen($html) && $depth > 0) {
            $nextOpen = strpos($html, $openPattern, $pos);
            $nextClose = strpos($html, $closePattern, $pos);

            if ($nextClose === false) {
                // Malformed HTML - couldn't find closing tag
                return null;
            }

            if ($nextOpen !== false && $nextOpen < $nextClose) {
                $depth++;
                $pos = $nextOpen + strlen($openPattern);
            } else {
                $depth--;
                $pos = $nextClose + strlen($closePattern);
            }
        }

        return $depth === 0 ? substr($html, $start, $pos - $start) : null;
    }

    public function render(): Closure
    {
        return function (array $data) {
            $this->processMarkdown($this->getMarkdownContent($data['slot'] ?? null));

            return view($this->layout)->with([
                'content' => $this->content,
                'toc' => $this->withToc ? $this->toc : null,
            ]);
        };
    }
}
