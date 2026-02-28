<?php

namespace Naykel\Gotime\Extensions\Markdown\Services;

/**
 * Builds Torchlight syntax-highlighted code components.
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
