<?php

namespace Naykel\Gotime\Extensions\Markdown\Component\Styles;

/**
 * Simple button-style collapsible component.
 *
 * Minimal collapse with:
 * - Simple button with no card styling
 * - No icon
 * - Lightweight and clean
 */
class Button implements StyleInterface
{
    public function render(string $content, array $attributes): string
    {
        $title = $attributes['title'] ?? 'Show More';
        $opened = isset($attributes['opened']) ? 'true' : 'false';
        $customClass = $attributes['class'] ?? '';

        $wrapperClass = htmlspecialchars(trim('mt-05 mb ' . $customClass));
        $titleEscaped = htmlspecialchars($title);

        return <<<HTML
            <div x-data="{ open: {$opened} }" class="{$wrapperClass}">
                <button x-on:click="open = !open" class="btn sm">
                    <span>{$titleEscaped}</span>
                </button>
                <div x-show="open" x-collapse class="mt-05">
                    {$content}
                </div>
            </div>
        HTML;
    }
}
