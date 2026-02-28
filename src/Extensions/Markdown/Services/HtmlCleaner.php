<?php

namespace Naykel\Gotime\Extensions\Markdown\Services;

/**
 * Cleans and formats HTML output.
 */
class HtmlCleaner
{
    /**
     * Clean HTML by removing Blade/Livewire comment artifacts.
     */
    public function clean(string $html): string
    {
        // Remove Livewire/Blade comment artifacts
        $html = preg_replace('/<!--\[if (BLOCK|ENDBLOCK)\]><!\\\[endif\]-->/', '', $html);

        // Remove outermost div with wire:snapshot (Livewire wrapper) but keep its contents
        $html = preg_replace('/^<div[^>]*wire:snapshot="[^"]*"[^>]*>\s*(.*?)\s*<\/div>$/s', '$1', trim($html));

        // Remove remaining wire attributes
        $html = preg_replace('/\s*wire:snapshot="[^"]*"/', '', $html);
        $html = preg_replace('/\s*wire:effects="[^"]*"/', '', $html);
        $html = preg_replace('/\s*wire:id="[^"]*"/', '', $html);

        // Remove blank lines
        $lines = explode("\n", $html);
        $html = implode("\n", array_filter($lines, fn($line) => trim($line) !== ''));

        return $html;
    }

    /**
     * Format HTML with consistent indentation.
     */
    public function format(string $html): string
    {
        $formatted = '';
        $indentLevel = 0;
        $indentString = '    '; // 4 spaces

        // Self-closing/void elements that don't need closing tags
        $voidElements = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];

        // Elements that should keep content inline (not add newlines around content)
        $inlineElements = ['a', 'strong', 'em', 'b', 'i', 'small', 'code', 'label'];

        // Split HTML into tokens (tags and text content)
        preg_match_all('/(<[^>]+>|[^<]+)/', $html, $matches);
        $tokens = $matches[0];

        $i = 0;
        while ($i < count($tokens)) {
            $token = trim($tokens[$i]);

            if (empty($token)) {
                $i++;

                continue;
            }

            // Check if it's a tag
            if (preg_match('/^</', $token)) {
                // Closing tag
                if (preg_match('/^<\/([a-z0-9]+)>$/i', $token, $match)) {
                    $indentLevel--;
                    $formatted .= str_repeat($indentString, max(0, $indentLevel)) . $token . "\n";
                }
                // Self-closing tag or void element
                elseif (preg_match('/\/>$/', $token) || preg_match('/<(' . implode('|', $voidElements) . ')[\s>\/]/i', $token)) {
                    $formatted .= str_repeat($indentString, $indentLevel) . $token . "\n";
                }
                // Opening tag
                elseif (preg_match('/<([a-z0-9]+)[\s>]/i', $token, $match)) {
                    $tagName = strtolower($match[1]);

                    // Check if this is an inline element with ONLY text content (no nested tags)
                    if (in_array($tagName, $inlineElements) && isset($tokens[$i + 1])) {
                        $nextToken = trim($tokens[$i + 1]);
                        // Only inline if next is text (not a tag) and closing tag follows
                        if (! preg_match('/^</', $nextToken) && isset($tokens[$i + 2]) && preg_match('/^<\/' . $tagName . '>$/i', trim($tokens[$i + 2]))) {
                            // Inline element with simple text - keep on same line
                            $formatted .= str_repeat($indentString, $indentLevel) . $token . $nextToken . trim($tokens[$i + 2]) . "\n";
                            $i += 3;

                            continue;
                        }
                    }

                    // Block element (or inline with nested content)
                    $formatted .= str_repeat($indentString, $indentLevel) . $token . "\n";
                    $indentLevel++;
                }
                // Other tags (comments, etc)
                else {
                    $formatted .= str_repeat($indentString, $indentLevel) . $token . "\n";
                }
            }
            // Text content
            else {
                $trimmed = trim($token);
                if ($trimmed !== '') {
                    $formatted .= str_repeat($indentString, $indentLevel) . $trimmed . "\n";
                }
            }

            $i++;
        }

        return trim($formatted);
    }

    /**
     * Clean and format HTML in one call.
     */
    public function cleanAndFormat(string $html): string
    {
        $cleaned = $this->clean($html);

        return $this->format($cleaned);
    }

    /**
     * Strip Torchlight annotations from code.
     */
    public function stripTorchlightAnnotations(string $code): string
    {
        $lines = explode("\n", $code);
        $cleaned = [];

        foreach ($lines as $line) {
            // Combine all regex patterns into one for better performance
            $cleanedLine = preg_replace(
                '/\s*(?:\/\/|#|<!--)\s*\[tl!.*?\].*?(?:-->)?$/i',
                '',
                $line
            );

            $cleaned[] = $cleanedLine;
        }

        return implode("\n", $cleaned);
    }
}
