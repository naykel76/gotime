<?php

namespace Naykel\Gotime\Extensions\Markdown\Services;

use Illuminate\Support\Facades\Blade;

/**
 * Renders Torchlight syntax-highlighted code blocks.
 */
class TorchlightRenderer
{
    /**
     * Build the Torchlight component string (without rendering).
     */
    public function buildComponentString(string $code, string $language, bool $verbatim): string
    {
        $wrappedCode = $verbatim
            ? '@verbatim' . $code . '@endverbatim'
            : $code;

        return '<x-torchlight-code language="' . $language . '">' . $wrappedCode . '</x-torchlight-code>';
    }

    /**
     * Render Torchlight code component with syntax highlighting.
     *
     * @link https://claude.ai/share/b9398274-5ba4-4478-a966-63fad4229068
     *
     * IMPORTANT: Blade compilation happens in TWO stages for components
     * Stage 1: User wraps in @verbatim to prevent Blade from executing {{ }} syntax
     * Stage 2: verbatim may be applied again here since we call Blade::render() manually
     * Both are needed!
     */
    public function render(string $code, string $language, bool $verbatim): string
    {
        $torchlight = $this->buildComponentString($code, $language, $verbatim);

        return Blade::render($torchlight);
    }

    /**
     * Get Torchlight language with override support.
     */
    public function getLanguage(array $attributes, string $defaultLanguage): string
    {
        $override = $this->getLanguageOverride($attributes);

        return $override ?? $defaultLanguage;
    }

    /**
     * Get code language override from code-X flag.
     */
    private function getLanguageOverride(array $attributes): ?string
    {
        foreach ($attributes as $key => $value) {
            if (preg_match('/^code-(.+)$/', $key, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}
