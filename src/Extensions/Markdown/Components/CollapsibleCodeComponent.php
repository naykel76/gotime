<?php

namespace Naykel\Gotime\Extensions\Markdown\Components;

use Illuminate\Support\Facades\Blade;
use Naykel\Gotime\Extensions\Markdown\Services\HtmlCleaner;
use Naykel\Gotime\Extensions\Markdown\Services\TorchlightRenderer;

class CollapsibleCodeComponent
{
    use CopyButtonTrait;

    public static function render(string $code, string $language = 'html', bool $verbatim = true, string $viewLabel = 'View Code', string $copyLabel = 'Copy Code'): string
    {
        $torchlight = new TorchlightRenderer;
        $htmlCleaner = new HtmlCleaner;

        $uniqueId = 'code-' . \Illuminate\Support\Str::random(8);
        $cleanCode = $htmlCleaner->stripTorchlightAnnotations($code);
        $escapedRawCode = htmlspecialchars(rtrim($cleanCode));

        $componentString = $torchlight->buildComponentString($code, $language, $verbatim);
        $renderedCode = Blade::render($componentString);

        $copyJs = self::generateCopyJs($uniqueId);
        $escapedViewLabel = htmlspecialchars($viewLabel);
        $escapedCopyLabel = htmlspecialchars($copyLabel);

        return <<<HTML
            <div x-data="{ open: false }" class="mt">
                <textarea id="{$uniqueId}-raw" style="display: none;">{$escapedRawCode}</textarea>
                <hr>
                <div class="flex items-center gap-05">
                    <button x-on:click="open = !open" class="btn sm">
                        <span>{$escapedViewLabel}</span>
                    </button>
                    <div x-data="{ copied: false }">
                        <button @click="{$copyJs}" class="btn sm"
                            title="{$escapedCopyLabel}"
                            class="btn sm inline-flex items-center gap-1">
                            <svg x-show="!copied" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                            <svg x-show="copied" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span x-text="copied ? 'Copied!' : '{$escapedCopyLabel}'"></span>
                        </button>
                    </div>
                </div>
                <div x-show="open" x-collapse class="mt-05">
                    <pre id="{$uniqueId}">{$renderedCode}</pre>
                </div>
            </div>
        HTML;
    }
}
