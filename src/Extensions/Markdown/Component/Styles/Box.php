<?php

namespace Naykel\Gotime\Extensions\Markdown\Component\Styles;

/**
 * Simple box component for notes, warnings, tips, etc.
 *
 * Renders content in a styled box with:
 * - Optional title displayed as a heading
 * - Border and background color
 * - Support for semantic classes (info, warning, danger, success)
 * - Always visible (not collapsible)
 */
class Box implements StyleInterface
{
    public function render(string $content, array $attributes): string
    {
        $title = $attributes['title'] ?? null;
        $customClass = $attributes['class'] ?? '';

        $wrapperClass = htmlspecialchars(trim('rounded-lg px-1.5 py-1 bdr ' . $customClass));

        if ($title) {
            $titleEscaped = htmlspecialchars($title);
            $titleHtml = <<<HTML
                <div class="font-semibold mb-0.5">{$titleEscaped}</div>
            HTML;
        } else {
            $titleHtml = '';
        }

        return <<<HTML
            <div class="{$wrapperClass}">
                {$titleHtml}
                {$content}
            </div>
        HTML;
    }
}
