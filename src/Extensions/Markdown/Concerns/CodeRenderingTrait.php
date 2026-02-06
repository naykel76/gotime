<?php

namespace Naykel\Gotime\Extensions\Markdown\Concerns;

use Illuminate\Support\Facades\Blade;

trait CodeRenderingTrait
{
    /**
     * Cache for cleaned code to avoid repeated processing
     */
    private array $cleanCodeCache = [];

    /**
     * Get code language override from code-X flag
     */
    private function getCodeLanguageOverride(array $attributes): ?string
    {
        foreach ($attributes as $key => $value) {
            if (preg_match('/^code-(.+)$/', $key, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Get Torchlight language (with override support)
     */
    private function getTorchlightLanguage(array $attributes, string $defaultLanguage): string
    {
        $override = $this->getCodeLanguageOverride($attributes);

        return $override ?? $defaultLanguage;
    }

    /**
     * Strip Torchlight annotations from code
     */
    private function stripTorchlightAnnotations(string $code): string
    {
        // Cache cleaned code to avoid repeated processing
        $hash = md5($code);
        if (isset($this->cleanCodeCache[$hash])) {
            return $this->cleanCodeCache[$hash];
        }

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

        $result = implode("\n", $cleaned);
        $this->cleanCodeCache[$hash] = $result;

        return $result;
    }

    /**
     * Build the Torchlight component string (without rendering).
     */
    public function buildTorchlightString(string $code, string $language, bool $verbatim): string
    {
        $wrappedCode = $verbatim
            ? '@verbatim' . $code . '@endverbatim'
            : $code;

        return '<x-torchlight-code language="' . $language . '">' . $wrappedCode . '</x-torchlight-code>';
    }

    /**
     * Render Torchlight code component with syntax highlighting and automatic copy button.
     *
     * Output: <x-torchlight-code>...</x-torchlight-code> or
     *         <x-torchlight-code>@verbatim...@endverbatim</x-torchlight-code>
     *
     * @link https://claude.ai/share/b9398274-5ba4-4478-a966-63fad4229068
     *
     * IMPORTANT: Blade compilation happens in TWO stages for components
     * ==================================================================
     *
     * Stage 1 (User's view file):
     * ---------------------------
     * User wraps in @verbatim to prevent Blade from executing {{ }} syntax
     * before the component receives it. This is because the component will
     * render the code before we put it inside the torchlight component.
     *
     * Stage 2 (Inside this method):
     * -----------------------------
     * verbatim may be applied again which seems redundant but is necessary
     * because we are calling Blade::render() manually here and may need to
     * prevent execution again.
     *
     * Both are needed!
     */
    public function renderCodeBlock(string $code, string $language, bool $verbatim, bool $isSelectable = false): string
    {
        $torchlight = $this->buildTorchlightString($code, $language, $verbatim);
        $renderedCode = Blade::render($torchlight);

        return $this->wrapCodeWithCopyButton($code, $renderedCode, $isSelectable);
    }

    /**
     * Wrap rendered code with a copy button
     */
    private function wrapCodeWithCopyButton(string $rawCode, string $renderedCode, bool $isSelectable = false): string
    {
        $uniqueId = 'code-' . \Illuminate\Support\Str::random(8);
        $cleanCode = $this->stripTorchlightAnnotations($rawCode);
        // Trim trailing newlines/whitespace from code for cleaner copying
        $cleanCode = rtrim($cleanCode);
        $escapedCode = htmlspecialchars($cleanCode);
        $copyJs = $this->getCopyButtonJs($uniqueId);

        $selectableClass = $isSelectable ? ' selectable-code-block' : '';
        $selectableAttr = $isSelectable ? ' data-selectable="true"' : '';

        return '
            <div class="relative code-block-wrapper' . $selectableClass . '"' . $selectableAttr . ' style="position: relative; isolation: isolate;' . ($isSelectable ? ' cursor: pointer; transition: all 0.2s ease;' : '') . '">
                <textarea id="' . $uniqueId . '-raw" style="display: none;">' . $escapedCode . '</textarea>
                <div style="position: absolute; top: 0.5rem; right: 0.5rem; z-index: 10;">
                    <div x-data="{ copied: false }">
                        <button @click.stop="' . $copyJs . '" 
                            class="btn xs"
                            :class="copied ? \'bg-sky-500\' : \'bg-sky-300\'"
                            x-text="copied ? \'Copied!\' : \'Copy\'">
                        </button>
                    </div>
                </div>
                <pre id="' . $uniqueId . '">' . $renderedCode . '</pre>
            </div>';
    }

    /**
     * Generate copy button JavaScript
     */
    private function getCopyButtonJs(string $elementId): string
    {
        return "
            const code = document.getElementById('{$elementId}-raw').value;
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(code);
            } else {
                const textarea = document.createElement('textarea');
                textarea.value = code;
                textarea.style.position = 'fixed';
                textarea.style.left = '-999999px';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }
            copied = true;
            setTimeout(() => copied = false, 2000);
        ";
    }

    /**
     * Build a standalone copy button
     */
    public function buildCopyButton(string $uniqueId, string $label = 'Copy Code'): string
    {
        $copyJs = $this->getCopyButtonJs($uniqueId);

        return '
            <div x-data="{ copied: false }">
                <button @click="' . $copyJs . '" 
                    class="btn sm"
                    :class="copied ? \'bg-sky-500\' : \'bg-sky-300\'"
                    x-text="copied ? \'Copied!\' : \'' . $label . '\'">
                </button>
            </div>';
    }

    /**
     * Build a collapsible section with view and copy buttons (the full unit)
     */
    public function buildCollapsibleSection(string $code, string $language, bool $verbatim, string $viewLabel, string $copyLabel): string
    {
        $uniqueId = 'code-' . \Illuminate\Support\Str::random(8);
        $cleanCode = $this->stripTorchlightAnnotations($code);
        $rawCode = htmlspecialchars($cleanCode);
        $renderedCode = $this->renderCodeBlock($code, $language, $verbatim);
        $copyJs = $this->getCopyButtonJs($uniqueId);

        return '
            <div x-data="{ open: false }" class="mt-05 mb">
                <textarea id="' . $uniqueId . '-raw" style="display: none;">' . $rawCode . '</textarea>
                <div class="flex items-center gap-05">
                    <button x-on:click="open = !open" class="btn sm">
                        <span>' . htmlspecialchars($viewLabel) . '</span>
                    </button>
                    <div x-data="{ copied: false }" style="position: static;">
                        <button @click="' . $copyJs . '" class="btn sm"
                            :class="copied ? \'bg-sky-500\' : \'bg-sky-300\'"
                            x-text="copied ? \'Copied!\' : \'' . $copyLabel . '\'"
                            style="position: static; display: inline-block;">
                        </button>
                    </div>
                </div>
                <div x-show="open" x-collapse class="mt-05">
                    ' . $renderedCode . '
                </div>
            </div>';
    }

    /**
     * Clean HTML by removing Blade/Livewire comment artifacts and formatting with proper indentation
     */
    private function cleanRenderedHtml(string $html): string
    {
        // Remove Livewire/Blade comment artifacts
        $html = preg_replace('/<!--\[if (BLOCK|ENDBLOCK)\]><!\\[endif\\]-->/', '', $html);

        // Remove outermost div with wire:snapshot (Livewire wrapper) but keep its contents
        $html = preg_replace('/^<div[^>]*wire:snapshot="[^"]*"[^>]*>\s*(.*?)\s*<\/div>$/s', '$1', trim($html));

        // Remove remaining wire attributes
        $html = preg_replace('/\s*wire:snapshot="[^"]*"/', '', $html);
        $html = preg_replace('/\s*wire:effects="[^"]*"/', '', $html);
        $html = preg_replace('/\s*wire:id="[^"]*"/', '', $html);

        // Remove blank lines
        $lines = explode("\n", $html);
        $html = implode("\n", array_filter($lines, fn($line) => trim($line) !== ''));

        // Format with custom formatter
        return $this->formatHtml($html);
    }

    /**
     * Format HTML with consistent indentation
     */
    private function formatHtml(string $html): string
    {
        $formatted = '';
        $indentLevel = 0;
        $indentString = '    '; // 4 spaces

        // Self-closing/void elements that don't need closing tags
        $voidElements = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];

        // Elements that should keep content inline (not add newlines around content)
        // Removed span since it often contains block elements
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
}
