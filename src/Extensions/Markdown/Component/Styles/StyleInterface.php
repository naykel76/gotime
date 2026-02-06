<?php

namespace Naykel\Gotime\Extensions\Markdown\Component\Styles;

/**
 * Interface for component style renderers.
 *
 * Each style implements this interface to provide custom rendering logic.
 */
interface StyleInterface
{
    /**
     * Render the component style.
     *
     * @param  string  $content  The HTML content to wrap
     * @param  array  $attributes  Parsed attributes (title, class, opened, etc.)
     * @return string Rendered HTML
     */
    public function render(string $content, array $attributes): string;
}
