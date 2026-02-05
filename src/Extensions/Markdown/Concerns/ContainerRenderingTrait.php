<?php

namespace Naykel\Gotime\Extensions\Markdown\Concerns;

trait ContainerRenderingTrait
{
    /**
     * Build a collapsible wrapper (accordion-style) for any content.
     *
     * Creates an Alpine.js powered collapsible section with a toggle button.
     * Can be used for code blocks, markdown content, or any HTML content.
     *
     * @param string $content The HTML content to wrap
     * @param string $buttonLabel The text for the toggle button
     * @param bool $openByDefault Whether the section should start expanded
     * @param string $additionalClasses Additional CSS classes for the wrapper
     */
    public function buildCollapsibleWrapper(
        string $content,
        string $buttonLabel = 'Show More',
        bool $openByDefault = false,
        string $additionalClasses = ''
    ): string {
        $openState = $openByDefault ? 'true' : 'false';
        $classes = trim('mt-05 mb ' . $additionalClasses);

        return '
            <div x-data="{ open: ' . $openState . ' }" class="' . htmlspecialchars($classes) . '">
                <button x-on:click="open = !open" class="btn sm">
                    <span>' . htmlspecialchars($buttonLabel) . '</span>
                </button>
                <div x-show="open" x-collapse class="mt-05">
                    ' . $content . '
                </div>
            </div>';
    }

    /**
     * Build a simple box container with optional title.
     *
     * Creates a container div with an optional title section and content area.
     * Perfect for callouts, notes, alerts, or any boxed content.
     *
     * @param string $content The HTML content to display
     * @param string|null $title Optional title for the box
     * @param string $additionalClasses Additional CSS classes for the box
     */
    public function buildBox(
        string $content,
        ?string $title = null,
        string $additionalClasses = ''
    ): string {
        $classes = trim('bx ' . $additionalClasses);
        $output = '<div class="' . htmlspecialchars($classes) . '">';

        if ($title !== null && $title !== '') {
            $output .= '<div class="bx-title">' . htmlspecialchars($title) . '</div>';
        }

        $output .= '<div class="bx-content">' . $content . '</div>';
        $output .= '</div>';

        return $output;
    }

    /**
     * Build a collapsible box (combination of collapse + box).
     *
     * Creates a box with collapsible content - useful for FAQ sections,
     * expandable notes, or any content that should be hidden by default.
     *
     * @param string $content The HTML content to display
     * @param string $title The title (required for collapsible boxes)
     * @param bool $openByDefault Whether the section should start expanded
     * @param string $additionalClasses Additional CSS classes for the wrapper
     */
    public function buildCollapsibleBox(
        string $content,
        string $title = 'Show More',
        bool $openByDefault = false,
        string $additionalClasses = ''
    ): string {
        // For now, just use collapsible wrapper
        // In the future, you could combine box styling with collapse behavior
        return $this->buildCollapsibleWrapper($content, $title, $openByDefault, $additionalClasses);
    }
}
