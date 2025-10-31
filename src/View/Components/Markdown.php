<?php

namespace Naykel\Gotime\View\Components;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class Markdown extends Component
{
    /**
     * Raw markdown file content loaded from disk
     */
    protected string $file;

    /**
     * Processed HTML ready for display in the view. This contains the markdown
     * converted to HTML, with any TOC extracted and content wrapped in a
     * .markdown-content div.
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

        // Convert markdown to HTML and prepare for rendering
        $this->processMarkdown();
    }

    /**
     * Converts raw markdown to HTML and structures it for display.
     *
     * This method:
     * 1. Renders the markdown file to HTML using the @markdown Blade directive
     * 2. Searches for a table of contents (TOC) marked with data-toc attribute
     * 3. If TOC exists: extracts it and places it before the main content
     * 4. Wraps the main content in a .markdown-content div for styling
     *
     * The result is stored in $renderedContent, ready to pass to the view.
     *
     * Why? Processing here (instead of in the view) allows us to:
     * - Manipulate the HTML structure (extract TOC, wrap content) before
     *   rendering making it easier to style and control layout
     * - Separate concerns: component handles logic, view handles display
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
            'renderedContent' => $this->renderedContent,
        ]);
    }
}
