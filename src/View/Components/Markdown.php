<?php

namespace Naykel\Gotime\View\Components;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;


class Markdown extends Component
{
    /**
     * Holds the raw markdown file content
     * @var string
     */
    protected string $file;

    /**
     * Holds the processed HTML content to be rendered
     * @var string
     */
    protected string $renderedContent;

    public function __construct(
        protected string $path,             // resource/views path excluding extension
        protected string $fullPath = ''     // full path excluding extension. allows absolute paths
    ) {
        if (! empty($fullPath)) {
            $this->file = getFile($this->fullPath . '.md');
        } else {
            $this->file = getFile($this->path . '.md');
        }

        // Process the markdown file into HTML
        $this->processMarkdown();
    }

    /**
     * Converts markdown file content to HTML, extracts TOC if present,
     * and wraps the content in a styled div.
     */
    protected function processMarkdown(): void
    {
        $rendered = Blade::render('@markdown($file)', ['file' => $this->file]);

        // Match by data attribute instead of class
        if (preg_match('/<div[^>]*data-toc[^>]*>.*?<\/div>/s', $rendered, $tocMatch)) {
            $toc = $tocMatch[0];
            $content = preg_replace('/<div[^>]*data-toc[^>]*>.*?<\/div>/s', '', $rendered, 1);
            $this->renderedContent = $toc . '<div class="markdown-content">' . trim($content) . '</div>';
        } else {
            $this->renderedContent = '<div class="markdown-content">' . $rendered . '</div>';
        }
    }

    public function render()
    {
        return view('gotime::components.markdown')->with([
            'renderedContent' => $this->renderedContent
        ]);
    }
}
