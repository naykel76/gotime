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
    private TorchlightRenderer $torchlight;
    private HtmlCleaner $htmlCleaner;

    public function __construct()
    {
        $this->torchlight = new TorchlightRenderer;
        $this->htmlCleaner = new HtmlCleaner;
    }

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

        $isCollapsible = isset($attributes['collapse']);
        $isSelectable = isset($attributes['selectable']);
        $wrapperClass = isset($attributes['class']) ? ' class="' . htmlspecialchars($attributes['class']) . '"' : '';
        $title = $attributes['title'] ?? 'Show Code';

        if (isset($attributes['preview'])) {
            return $this->renderWithPreview($content, $attributes, $language, $isCollapsible, $wrapperClass, $title);
        }

        $codeOverride = $this->torchlight->getLanguage($attributes, '');
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
        $codeLanguage = $this->torchlight->getLanguage($attributes, $language);

        $hasCode = isset($attributes['code']);
        $hasOutput = isset($attributes['output']);

        if ($isCollapsible) {
            return $this->buildCollapsiblePreview($rendered, $content, $codeLanguage, $hasCode, $hasOutput, $title);
        }

        return $this->buildInlinePreview($rendered, $content, $codeLanguage, $hasCode, $hasOutput);
    }

    private function buildCollapsiblePreview(
        string $rendered,
        string $content,
        string $codeLanguage,
        bool $hasCode,
        bool $hasOutput,
        string $title
    ): string {
        $output = $rendered;

        if ($hasCode) {
            $output .= $this->buildCollapsibleCodeSection($content, $codeLanguage, true, 'View Code', 'Copy Code');
        }

        if ($hasOutput) {
            $generatedHtml = $this->htmlCleaner->cleanAndFormat(Blade::render($content));
            $output .= $this->buildCollapsibleCodeSection($generatedHtml, $codeLanguage, false, $title, 'Copy Output');
        }

        return $output;
    }

    private function buildInlinePreview(
        string $rendered,
        string $content,
        string $codeLanguage,
        bool $hasCode,
        bool $hasOutput
    ): string {
        if ($hasCode) {
            $codeBlock = $this->renderCodeBlock($content, $codeLanguage, true);
            return $rendered . $codeBlock;
        }

        if ($hasOutput) {
            $generatedHtml = $this->htmlCleaner->cleanAndFormat(Blade::render($content));
            $codeBlock = $this->renderCodeBlock($generatedHtml, $codeLanguage, false);
            return $rendered . $codeBlock;
        }

        return $rendered;
    }

    private function renderCode(string $content, string $language, bool $isCollapsible, bool $isSelectable, string $title): string
    {
        if ($isCollapsible) {
            return $this->buildCollapsibleCodeSection($content, $language, true, $title, 'Copy Code');
        }

        return $this->renderCodeBlock($content, $language, true, $isSelectable);
    }

    private function renderCodeBlock(string $code, string $language, bool $verbatim, bool $isSelectable = false): string
    {
        $torchlight = $this->torchlight->buildComponentString($code, $language, $verbatim);
        $renderedCode = Blade::render($torchlight);
        $cleanCode = $this->htmlCleaner->stripTorchlightAnnotations($code);

        return $this->wrapWithCopyButton(rtrim($cleanCode), $renderedCode, $isSelectable);
    }

    private function buildCollapsibleCodeSection(string $code, string $language, bool $verbatim, string $viewLabel, string $copyLabel): string
    {
        $uniqueId = 'code-' . \Illuminate\Support\Str::random(8);
        $cleanCode = $this->htmlCleaner->stripTorchlightAnnotations($code);
        $rawCode = htmlspecialchars(rtrim($cleanCode));

        $torchlight = $this->torchlight->buildComponentString($code, $language, $verbatim);
        $renderedCode = Blade::render($torchlight);

        $copyJs = $this->generateCopyJs($uniqueId);
        $escapedViewLabel = htmlspecialchars($viewLabel);
        $escapedCopyLabel = htmlspecialchars($copyLabel);

        return <<<HTML
            <div x-data="{ open: false }" class="mt-05 mb">
                <textarea id="{$uniqueId}-raw" style="display: none;">{$rawCode}</textarea>
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
            <div class="relative code-block-wrapper{$selectableClass}"{$selectableAttr} style="position: relative; isolation: isolate;{$selectableStyle}">
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
