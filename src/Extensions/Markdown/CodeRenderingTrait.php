<?php

namespace Naykel\Gotime\Extensions\Markdown;

use Illuminate\Support\Facades\Blade;

trait CodeRenderingTrait
{
    /**
     * Get code language override from +code-X flag
     */
    private function getCodeLanguageOverride(array $info): ?string
    {
        foreach ($info as $flag) {
            if (preg_match('/^\+code-(.+)$/', $flag, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Get Torchlight language (with override support)
     */
    private function getTorchlightLanguage(array $info, string $defaultLanguage): string
    {
        $override = $this->getCodeLanguageOverride($info);

        return $override ?? $defaultLanguage;
    }

    /**
     * Render a code block with Torchlight highlighting
     */
    private function renderCodeBlock(string $code, string $language, bool $verbatim): string
    {
        $wrappedCode = $verbatim
            ? '@verbatim' . $code . '@endverbatim'
            : $code;

        $torchlight = '<x-torchlight-code language="' . $language . '">' . $wrappedCode . '</x-torchlight-code>';

        return '<pre>' . Blade::render($torchlight) . '</pre>';
    }

    /**
     * Render Torchlight code for use in collapsible sections
     */
    private function renderTorchlightCode(string $code, string $language, bool $verbatim): string
    {
        $wrappedCode = $verbatim
            ? '@verbatim' . $code . '@endverbatim'
            : $code;

        $torchlight = '<x-torchlight-code language="' . $language . '">' . $wrappedCode . '</x-torchlight-code>';

        return Blade::render($torchlight);
    }

    /**
     * Generate copy button JavaScript
     */
    private function getCopyButtonJs(string $elementId): string
    {
        return "
            const code = document.getElementById('{$elementId}').getAttribute('data-code');
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
     * Build a collapsible section with view/copy buttons
     */
    private function buildCollapsibleSection(string $code, string $language, bool $verbatim, string $viewLabel, string $copyLabel): string
    {
        $uniqueId = 'code-' . \Illuminate\Support\Str::random(8);
        $rawCode = htmlspecialchars($code);
        $renderedCode = $this->renderTorchlightCode($code, $language, $verbatim);
        $copyJs = $this->getCopyButtonJs($uniqueId);
        
        return '
            <div x-data="{ open: false }" class="mt-05 mb">
                <div class="flex items-center gap-05">
                    <button x-on:click="open = !open" class="btn sm">
                        <span>' . htmlspecialchars($viewLabel) . '</span>
                    </button>
                    <button x-data="{ copied: false }" @click="' . $copyJs . '" class="btn sm"
                        :class="copied ? \'bg-sky-500\' : \'bg-sky-300\'"
                        x-text="copied ? \'Copied!\' : \'' . $copyLabel . '\'">
                    </button>
                </div>
                <div x-show="open" x-collapse class="mt-05">
                    <pre id="' . $uniqueId . '" data-code="' . $rawCode . '">' . $renderedCode . '</pre>
                </div>
            </div>';
    }

    /**
     * Format HTML with proper indentation without breaking SVG or attributes
     */
    private function formatHtml(string $html): string
    {
        $html = preg_replace('/>\s+</', '><', $html);
        
        $formatted = '';
        $indent = 0;
        $indentString = '    ';
        $voidElements = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];
        
        $tokens = preg_split('/(<[^>]+>)/', $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        
        foreach ($tokens as $token) {
            $token = trim($token);
            if (empty($token)) {
                continue;
            }
            
            if (preg_match('/^</', $token)) {
                if (preg_match('/^<\//', $token)) {
                    $indent--;
                    $formatted .= str_repeat($indentString, max(0, $indent)) . $token . "\n";
                } elseif (preg_match('/\/>$/', $token) || preg_match('/<(' . implode('|', $voidElements) . ')[\s>]/i', $token)) {
                    $formatted .= str_repeat($indentString, $indent) . $token . "\n";
                } else {
                    $formatted .= str_repeat($indentString, $indent) . $token . "\n";
                    $indent++;
                }
            } else {
                $formatted .= str_repeat($indentString, $indent) . $token . "\n";
            }
        }
        
        return trim($formatted);
    }
}