<?php

namespace Naykel\Gotime\Extensions\Markdown\Container\Layouts;

/**
 * Interface for container layout renderers.
 *
 * Each layout implements this interface to provide custom rendering logic.
 */
interface LayoutInterface
{
    /**
     * Render the container layout.
     *
     * @param string $content The HTML content to wrap
     * @param array $attributes Parsed attributes (title, class, opened, etc.)
     * @return string Rendered HTML
     */
    public function render(string $content, array $attributes): string;
}
