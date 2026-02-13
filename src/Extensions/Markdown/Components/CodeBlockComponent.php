<?php

namespace Naykel\Gotime\Extensions\Markdown\Components;

use Illuminate\Support\Facades\Blade;
use Naykel\Gotime\Extensions\Markdown\Services\HtmlCleaner;
use Naykel\Gotime\Extensions\Markdown\Services\TorchlightRenderer;

class CodeBlockComponent
{
    use CopyButtonTrait;

    public static function render(string $code, string $language = 'html'): string
    {
        $torchlight = new TorchlightRenderer;
        $htmlCleaner = new HtmlCleaner;

        $componentString = $torchlight->buildComponentString($code, $language, true);
        $renderedCode = Blade::render($componentString);
        $cleanCode = $htmlCleaner->stripTorchlightAnnotations($code);

        return self::wrapWithCopyButton(rtrim($cleanCode), $renderedCode);
    }

    private static function wrapWithCopyButton(string $rawCode, string $renderedCode): string
    {
        $uniqueId = 'code-' . \Illuminate\Support\Str::random(8);
        $copyJs = self::generateCopyJs($uniqueId);
        $escapedRawCode = htmlspecialchars($rawCode);

        return <<<HTML
            <div class="code-block-wrapper isolate relative">
                <textarea id="{$uniqueId}-raw" style="display: none;">{$escapedRawCode}</textarea>

                <div class="absolute top-05 right-05 z-10">
                    <div x-data="{ copied: false }">
                        <button @click.stop="{$copyJs}" class="btn dark aspect-square pxy-05">
                            <svg class="wh-1" x-show="!copied" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                            <svg class="wh-1" x-show="copied" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <pre id="{$uniqueId}">{$renderedCode}</pre>
            </div>
        HTML;
    }
}
