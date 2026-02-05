<?php

namespace Naykel\Gotime\Extensions\Markdown\Container\Layouts;

/**
 * Simple box layout for notes, warnings, tips, etc.
 *
 * Renders content in a styled box with:
 * - Optional title displayed as a heading
 * - Border and background color
 * - Support for semantic classes (info, warning, danger, success)
 * - Always visible (not collapsible)
 */
class Box implements LayoutInterface
{
    public function render(string $content, array $attributes): string
    {
        $title = $attributes['title'] ?? null;
        $customClass = $attributes['class'] ?? '';
        
        $wrapperClass = htmlspecialchars(trim('rounded-lg px-1.5 py-1 bdr ' . $customClass));
        
        $html = "<div class=\"{$wrapperClass}\">";
        
        if ($title) {
            $titleEscaped = htmlspecialchars($title);
            $html .= "<div class=\"font-semibold mb-0.5\">{$titleEscaped}</div>";
        }
        
        $html .= $content;
        $html .= '</div>';
        
        return $html;
    }
}
