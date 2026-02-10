<?php

namespace Naykel\Gotime\Extensions\Markdown;

use Illuminate\Support\Facades\Blade;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use Naykel\Gotime\Extensions\Markdown\Services\HtmlCleaner;
use Naykel\Gotime\Extensions\Markdown\Services\TorchlightRenderer;
use Naykel\Gotime\Extensions\Markdown\Support\AttributeParser;

class CodeRendererExtension implements ExtensionInterface, NodeRendererInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, $this, 100);
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        /** @var FencedCode $node */
        $flagString = $node->getInfo();
        $flagsArray = $node->getInfoWords();
        $language = $flagsArray[0] ?? 'html';
        $content = $node->getLiteral();

        $attributes = AttributeParser::parse($flagString);

        $isCollapsible = isset($attributes['collapse']) || isset($attributes['demo-folded']);
        $isSelectable = isset($attributes['selectable']);
        $wrapperClass = isset($attributes['class']) ? ' class="' . htmlspecialchars($attributes['class']) . '"' : '';
        $title = $attributes['title'] ?? 'Show Code';

        if (isset($attributes['demo']) || isset($attributes['demo-folded'])) {
            $attributes['preview'] = true;
            $attributes['code'] = true;
        }

        if (isset($attributes['preview'])) {
            return $this->renderWithPreview($content, $attributes, $language, $isCollapsible, $wrapperClass, $title);
        }

        $torchlight = new TorchlightRenderer;
        $codeOverride = $torchlight->getLanguage($attributes, '');
        if ($codeOverride) {
            return $this->renderCode($content, $codeOverride, $isCollapsible, $isSelectable, $title);
        }

        if (isset($attributes['code'])) {
            return $this->renderCode($content, $language, $isCollapsible, $isSelectable, $title);
        }

        return null;
    }

    private function renderWithPreview(
        string $content,
        array $attributes,
        string $language,
        bool $isCollapsible,
        string $wrapperClass,
        string $title
    ): string {
        $rendered = '<div' . $wrapperClass . '>' . Blade::render($content) . '</div>';
        
        $torchlight = new TorchlightRenderer;
        $htmlCleaner = new HtmlCleaner;
        $codeLanguage = $torchlight->getLanguage($attributes, $language);

        $hasCode = isset($attributes['code']);
        $hasOutput = isset($attributes['output']);

        if ($isCollapsible) {
            return $this->buildCollapsiblePreview($rendered, $content, $codeLanguage, $hasCode, $hasOutput, $title, $torchlight, $htmlCleaner);
        }

        return $this->buildInlinePreview($rendered, $content, $codeLanguage, $hasCode, $hasOutput, $torchlight, $htmlCleaner);
    }

    private function buildCollapsiblePreview(
        string $rendered,
        string $content,
        string $codeLanguage,
        bool $hasCode,
        bool $hasOutput,
        string $title,
        TorchlightRenderer $torchlight,
        HtmlCleaner $htmlCleaner
    ): string {
        $output = $rendered;

        if ($hasCode) {
            $output .= $this->buildCollapsibleCodeSection($content, $codeLanguage, true, 'View Code', 'Copy Code', $torchlight, $htmlCleaner);
        }

        if ($hasOutput) {
            $generatedHtml = $htmlCleaner->cleanAndFormat(Blade::render($content));
            $output .= $this->buildCollapsibleCodeSection($generatedHtml, $codeLanguage, false, $title, 'Copy Output', $torchlight, $htmlCleaner);
        }

        return $output;
    }

    private function buildInlinePreview(
        string $rendered,
        string $content,
        string $codeLanguage,
        bool $hasCode,
        bool $hasOutput,
        TorchlightRenderer $torchlight,
        HtmlCleaner $htmlCleaner
    ): string {
        if ($hasCode) {
            $codeBlock = $this->renderCodeBlock($content, $codeLanguage, true, $torchlight, $htmlCleaner);
            return $rendered . $codeBlock;
        }

        if ($hasOutput) {
            $generatedHtml = $htmlCleaner->cleanAndFormat(Blade::render($content));
            $codeBlock = $this->renderCodeBlock($generatedHtml, $codeLanguage, false, $torchlight, $htmlCleaner);
            return $rendered . $codeBlock;
        }

        return $rendered;
    }

    private function renderCode(string $content, string $language, bool $isCollapsible, bool $isSelectable, string $title): string
    {
        $torchlight = new TorchlightRenderer;
        $htmlCleaner = new HtmlCleaner;
        
        if ($isCollapsible) {
            return $this->buildCollapsibleCodeSection($content, $language, true, $title, 'Copy Code', $torchlight, $htmlCleaner);
        }

        return $this->renderCodeBlock($content, $language, true, $torchlight, $htmlCleaner, $isSelectable);
    }

    private function renderCodeBlock(string $code, string $language, bool $verbatim, TorchlightRenderer $torchlight, HtmlCleaner $htmlCleaner, bool $isSelectable = false): string
    {
        $componentString = $torchlight->buildComponentString($code, $language, $verbatim);
        $renderedCode = Blade::render($componentString);
        $cleanCode = $htmlCleaner->stripTorchlightAnnotations($code);

        return $this->wrapWithCopyButton(rtrim($cleanCode), $renderedCode, $isSelectable);
    }

    private function buildCollapsibleCodeSection(string $code, string $language, bool $verbatim, string $viewLabel, string $copyLabel, TorchlightRenderer $torchlight, HtmlCleaner $htmlCleaner): string
    {
        $uniqueId = 'code-' . \Illuminate\Support\Str::random(8);
        $cleanCode = $htmlCleaner->stripTorchlightAnnotations($code);
        $escapedRawCode = htmlspecialchars(rtrim($cleanCode));

        $componentString = $torchlight->buildComponentString($code, $language, $verbatim);
        $renderedCode = Blade::render($componentString);

        $copyJs = $this->generateCopyJs($uniqueId);
        $escapedViewLabel = htmlspecialchars($viewLabel);
        $escapedCopyLabel = htmlspecialchars($copyLabel);

        return <<<HTML
            <div x-data="{ open: false }" class="mt-05 mb">
                <textarea id="{$uniqueId}-raw" style="display: none;">{$escapedRawCode}</textarea>
                <div class="flex items-center gap-05">
                    <button x-on:click="open = !open" class="btn sm">
                        <span>{$escapedViewLabel}</span>
                    </button>
                    <div x-data="{ copied: false }" style="position: static;">
                        <button @click="{$copyJs}" class="btn sm"
                            :class="copied ? 'bg-sky-500' : 'bg-sky-300'"
                            x-text="copied ? 'Copied!' : '{$escapedCopyLabel}'"
                            style="position: static; display: inline-block;">
                        </button>
                    </div>
                </div>
                <div x-show="open" x-collapse class="mt-05">
                    <pre id="{$uniqueId}">{$renderedCode}</pre>
                </div>
            </div>
        HTML;
    }

    private function wrapWithCopyButton(string $rawCode, string $renderedCode, bool $isSelectable = false): string
    {
        $uniqueId = 'code-' . \Illuminate\Support\Str::random(8);
        $copyJs = $this->generateCopyJs($uniqueId);
        $escapedRawCode = htmlspecialchars($rawCode);

        $selectableClass = $isSelectable ? ' selectable-code-block' : '';
        $selectableStyle = $isSelectable ? ' cursor: pointer; transition: all 0.2s ease;' : '';
        $selectableAttr = $isSelectable ? ' data-selectable="true"' : '';

        return <<<HTML
            <div class="code-block-wrapper{$selectableClass}"{$selectableAttr} style="isolation: isolate;{$selectableStyle}">
                <textarea id="{$uniqueId}-raw" style="display: none;">{$escapedRawCode}</textarea>
                <div style="position: absolute; top: 0.5rem; right: 0.5rem; z-index: 10;">
                    <div x-data="{ copied: false }">
                        <button @click.stop="{$copyJs}" 
                            class="btn xs"
                            :class="copied ? 'bg-sky-500' : 'bg-sky-300'"
                            x-text="copied ? 'Copied!' : 'Copy'">
                        </button>
                    </div>
                </div>
                <pre id="{$uniqueId}">{$renderedCode}</pre>
            </div>
        HTML;
    }

    private function generateCopyJs(string $elementId): string
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
}
