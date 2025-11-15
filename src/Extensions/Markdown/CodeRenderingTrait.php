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
     * Build the Torchlight component string (without rendering).
     */
    public function buildTorchlightString(string $code, string $language, bool $verbatim): string
    {
        $wrappedCode = $verbatim
            ? '@verbatim' . $code . '@endverbatim'
            : $code;

        return '<pre><x-torchlight-code language="' . $language . '">' . $wrappedCode . '</x-torchlight-code></pre>';
    }

    /**
     * Render Torchlight code component with syntax highlighting.
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
    public function renderCodeBlock(string $code, string $language, bool $verbatim): string
    {
        $torchlight = $this->buildTorchlightString($code, $language, $verbatim);

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
     * Build a collapsible wrapper (just the accordion, no code inside)
     */
    public function buildCollapsibleWrapper(string $content, string $buttonLabel = 'Show Code'): string
    {
        return '
            <div x-data="{ open: false }" class="mt-05 mb">
                <button x-on:click="open = !open" class="btn sm">
                    <span>' . htmlspecialchars($buttonLabel) . '</span>
                </button>
                <div x-show="open" x-collapse class="mt-05">
                    ' . $content . '
                </div>
            </div>';
    }

    /**
     * Build a collapsible section with view and copy buttons (the full unit)
     */
    public function buildCollapsibleSection(string $code, string $language, bool $verbatim, string $viewLabel, string $copyLabel): string
    {
        $uniqueId = 'code-' . \Illuminate\Support\Str::random(8);
        $rawCode = htmlspecialchars($code);
        $renderedCode = $this->renderCodeBlock($code, $language, $verbatim);
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
