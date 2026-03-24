<?php

namespace Naykel\Gotime\Extensions\Markdown\Support;

/**
 * Parses attributes from markdown info strings.
 *
 * Provides static methods to extract attributes from strings like:
 * - collapse title="Show More" class="foo" opened
 */
class AttributeParser
{
    /**
     * Extracts an attribute value from a string.
     *
     * Searches for attrName="value" (quoted) or attrName=value (unquoted) patterns
     * and returns the extracted value. When formatAsHtml is true, returns a formatted
     * HTML attribute string (e.g., ' class="value"'), otherwise returns just the raw value.
     * Returns null if the attribute is not found.
     *
     * Examples:
     *   extractAttribute('class="foo"', 'class') => 'foo'
     *   extractAttribute('class="foo"', 'class', true) => ' class="foo"'
     *   extractAttribute('title=Hello', 'title') => 'Hello'
     *   extractAttribute('+title="Show Code"', '+title') => 'Show Code'
     */
    public static function extractAttribute(string $string, string $attrName, bool $formatAsHtml = false): ?string
    {
        $escapedAttr = preg_quote($attrName, '/');

        // Quoted: attr="value" or attr='value'
        if (preg_match("/{$escapedAttr}=([\"'])(.+?)\\1/", $string, $matches)) {
            $value = $matches[2];

            return $formatAsHtml ? ' ' . $attrName . '="' . htmlspecialchars($value) . '"' : $value;
        }
        // Unquoted: attr=value
        if (preg_match("/{$escapedAttr}=(\\S+)/", $string, $matches)) {
            $value = $matches[1];

            return $formatAsHtml ? ' ' . $attrName . '="' . htmlspecialchars($value) . '"' : $value;
        }

        return null;
    }

    /**
     * Parse all attributes from a string into an associative array.
     *
     * Extracts all key="value" or key=value pairs from the input string.
     * Useful for component blocks where you want all attributes at once.
     *
     * Example:
     *   parse('title="Hello" class="foo bar" opened')
     *   => ['title' => 'Hello', 'class' => 'foo bar', 'opened' => true]
     */
    public static function parse(string $string): array
    {
        $attributes = [];

        // Match quoted attributes: attr="value" or attr='value' (supports +attr="value" and hyphenated keys)
        preg_match_all('/([\w+-]+)=(["\'])(.+?)\2/', $string, $quotedMatches, PREG_SET_ORDER);
        foreach ($quotedMatches as $match) {
            // Strip + prefix from key if present
            $key = ltrim($match[1], '+');
            $attributes[$key] = $match[3];
        }

        // Match unquoted attributes: attr=value (supports +attr=value and hyphenated keys)
        preg_match_all('/([\w+-]+)=(\S+)/', $string, $unquotedMatches, PREG_SET_ORDER);
        foreach ($unquotedMatches as $match) {
            // Strip + prefix from key if present
            $key = ltrim($match[1], '+');
            // Skip if already captured as quoted
            if (! isset($attributes[$key])) {
                $attributes[$key] = $match[2];
            }
        }

        // Match boolean flags (words without = sign)
        // Remove all key=value pairs first, then split remaining words
        $withoutPairs = preg_replace('/[\w+-]+=(?:["\'][^"\']*["\']|\S+)/', '', $string);
        $words = preg_split('/\s+/', trim($withoutPairs), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($words as $word) {
            // Strip + prefix if present (+preview becomes preview)
            $key = ltrim($word, '+');
            $attributes[$key] = true;
        }

        return $attributes;
    }
}
