<?php

namespace Naykel\Gotime\Components;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class Markdown extends Component
{
    /**
     * Markdown content loaded from disk
     */
    protected string $fileContent;

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
    ) {
        $this->fileContent = $this->getFileContent();
        $this->processMarkdown();
    }

    /**
     * Load markdown file content from disk.
     * Defaults to resources/views/ for relative paths.
     */
    protected function getFileContent(): string
    {
        $filePath = $this->absolute
            ? $this->path . '.md'
            : resource_path('views/' . $this->path) . '.md';

        return getFile($filePath);
    }

    /**
     * Convert markdown to HTML and separate TOC from content.
     */
    protected function processMarkdown(): void
    {
        $rendered = $this->renderMarkdownToHtml();

        $this->toc = $this->extractToc($rendered);
        $this->content = $this->removeTocFromHtml($rendered);
    }

    /**
     * Render markdown content to HTML using Blade's markdown directive.
     */
    protected function renderMarkdownToHtml(): string
    {
        return Blade::render('@markdown($file)', ['file' => $this->fileContent]);
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

    public function render()
    {
        return view($this->layout)->with([
            'content' => $this->content,
            'toc' => $this->withToc ? $this->toc : null,
        ]);
    }
}
