<?php

namespace Naykel\Gotime\Extensions\Markdown\Component\Styles;

/**
 * Show more/less toggle component.
 *
 * Minimal expandable content with:
 * - "Show More" / "Show Less" toggle button
 * - No border or card styling
 * - Perfect for collapsing long content under headings
 */
class ShowMore implements StyleInterface
{
    public function render(string $content, array $attributes): string
    {
        $opened = isset($attributes['opened']) ? 'true' : 'false';
        $customClass = $attributes['class'] ?? '';

        $wrapperClass = htmlspecialchars(trim($customClass));
        $wrapperClassAttr = $wrapperClass ? " class=\"{$wrapperClass}\"" : '';

        return <<<HTML
            <div x-data="{ open: {$opened} }"{$wrapperClassAttr}>
                <div x-show="open" x-collapse class="my">
                    {$content}
                </div>
                <button x-on:click="open = !open" class="btn primary xs" x-text="open ? 'Show Less' : 'Show More'"> </button>
            </div>
        HTML;
    }
}
