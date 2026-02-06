<?php

namespace Naykel\Gotime\Extensions\Markdown;

use Illuminate\Support\Facades\Blade;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use Naykel\Gotime\Extensions\Markdown\Concerns\CodeRenderingTrait;
use Naykel\Gotime\Extensions\Markdown\Support\AttributeParser;

class CodeRendererExtension implements ExtensionInterface, NodeRendererInterface
{
    use CodeRenderingTrait;

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, $this, 100);
    }

    /**
     * Main rendering method that determines how to render a fenced code block.
     *
     * Handles multiple rendering modes including preview rendering (+preview),
     * code-only display (+code), and language overrides (+code-X). Returns null
     * if no special flags are present to let the default renderer handle it.
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        /** @var FencedCode $node */
        $flagString = $node->getInfo();         // 'html +preview +collapse class="bg-gray-100" title="Show Code"'
        $flagsArray = $node->getInfoWords();    // ['html', '+preview', '+collapse', 'class="bg-gray-100"', 'title="Show Code"']
        $language = $flagsArray[0] ?? 'html';
        $content = $node->getLiteral();

        // Parse all flags into attributes (strips + prefix)
        $attributes = AttributeParser::parse($flagString);

        // Configuration flags (now without + prefix)
        $isCollapsible = isset($attributes['collapse']);
        $isSelectable = isset($attributes['selectable']);
        $wrapperClass = isset($attributes['class']) ? ' class="' . htmlspecialchars($attributes['class']) . '"' : '';
        $title = $attributes['title'] ?? 'Show Code';

        // Handle preview flag
        if (isset($attributes['preview'])) {
            return $this->renderWithPreview($content, $attributes, $language, $isCollapsible, $wrapperClass, $title);
        }

        // Check for code-X override first (e.g. code-blade)
        $codeOverride = $this->getCodeLanguageOverride($attributes);
        if ($codeOverride) {
            return $this->renderCode($content, $codeOverride, $isCollapsible, $isSelectable, $title);
        }

        // Just code = show highlighted code only
        if (isset($attributes['code'])) {
            return $this->renderCode($content, $language, $isCollapsible, $isSelectable, $title);
        }

        // No flags = return null (let default renderer handle it)
        return null;
    }

    /**
     * Renders content with a live preview alongside optional code display.
     *
     * Handles the preview flag by showing the rendered output first, then optionally
     * includes source code or generated HTML (code, output) in either collapsible
     * or inline format based on configuration.
     */
    private function renderWithPreview(
        string $content,
        array $attributes,
        string $language,
        bool $isCollapsible,
        string $wrapperClass,
        string $title
    ): string {
        $rendered = '<div' . $wrapperClass . '>' . Blade::render($content) . '</div>';
        $codeLanguage = $this->getTorchlightLanguage($attributes, $language);

        $hasCode = isset($attributes['code']);
        $hasOutput = isset($attributes['output']);

        if ($isCollapsible) {
            return $this->buildCollapsiblePreview($rendered, $content, $codeLanguage, $hasCode, $hasOutput, $title);
        }

        return $this->buildInlinePreview($rendered, $content, $codeLanguage, $hasCode, $hasOutput);
    }

    /**
     * Builds a collapsible preview with rendered output and optional code sections.
     *
     * Creates accordion-style sections showing the rendered preview followed by
     * collapsible original code and/or generated HTML output based on the flags present.
     */
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
            $output .= $this->buildCollapsibleSection($content, $codeLanguage, true, 'View Code', 'Copy Code');
        }

        if ($hasOutput) {
            $generatedHtml = $this->cleanRenderedHtml(Blade::render($content));
            $output .= $this->buildCollapsibleSection($generatedHtml, $codeLanguage, false, $title, 'Copy Output');
        }

        return $output;
    }

    /**
     * Builds an inline preview with rendered output and optional code blocks.
     *
     * Shows the rendered preview immediately followed by code blocks displayed
     * inline (not collapsible) for original code and/or generated HTML output based on flags.
     */
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
            $generatedHtml = $this->cleanRenderedHtml(Blade::render($content));
            $codeBlock = $this->renderCodeBlock($generatedHtml, $codeLanguage, false);

            return $rendered . $codeBlock;
        }

        return $rendered;
    }

    /**
     * Renders code block in either collapsible or inline format.
     *
     * Used for +code and +code-X flags to display syntax-highlighted code blocks
     * either as collapsible sections or standard inline code blocks.
     */
    private function renderCode(string $content, string $language, bool $isCollapsible, bool $isSelectable, string $title): string
    {
        if ($isCollapsible) {
            return $this->buildCollapsibleSection($content, $language, true, $title, 'Copy Code');
        }

        return $this->renderCodeBlock($content, $language, true, $isSelectable);
    }
}
