<?php

namespace Naykel\Gotime\Extensions\Markdown\Component\Styles;

/**
 * Card-style collapsible component.
 *
 * Renders a collapsible section with:
 * - White background with border and shadow
 * - Full-width button with hover effect
 * - Chevron icon that rotates
 * - Perfect for FAQs and accordions
 */
class Card implements StyleInterface
{
    public function render(string $content, array $attributes): string
    {
        $title = $attributes['title'] ?? 'Show More';
        $opened = isset($attributes['opened']) ? 'true' : 'false';
        $customClass = $attributes['class'] ?? '';

        $wrapperClass = htmlspecialchars(trim('bg-white rounded-lg shadow-sm bdr bdr-gray-200 ' . $customClass));
        $titleEscaped = htmlspecialchars($title);

        return <<<HTML
            <div x-data="{ open: {$opened} }" class="{$wrapperClass}">
                <button type="button" x-on:click="open = !open" class="w-full px-1.5 py-1 flex items-center justify-between hover:bg-gray-50">
                    <span class="font-semibold txt-gray-900">{$titleEscaped}</span>
                    <svg class="wh-1" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="px-1.5 py-1 mxy-0 txt-gray-600">
                    {$content}
                </div>
            </div>
        HTML;
    }
}
