<?php

namespace Naykel\Gotime\Extensions\Markdown;

use Illuminate\Support\Facades\Blade;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

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
        $flagString = $node->getInfo();         // 'html +preview +collapse class="bg-gray-100" +title="Show Code"'
        $flagsArray = $node->getInfoWords();    // ['html', '+preview', '+collapse', 'class="bg-gray-100"', '+title="Show Code"']
        $language = $flagsArray[0] ?? 'html';
        $content = $node->getLiteral();

        // configuration flags
        $isCollapsible = in_array('+collapse', $flagsArray);
        $wrapperClass = $this->extractAttribute($flagString, 'class', true) ?? '';
        $title = $this->extractAttribute($flagString, '+title') ?? 'Show Code';

        // Handle +preview flag
        if (in_array('+preview', $flagsArray)) {
            return $this->renderWithPreview($content, $flagsArray, $language, $isCollapsible, $wrapperClass, $title);
        }

        // Check for +code-X override first (e.g., +code-blade)
        $codeOverride = $this->getCodeLanguageOverride($flagsArray);
        if ($codeOverride) {
            return $this->renderCode($content, $codeOverride, $isCollapsible, $title);
        }

        // Just +code = show highlighted code only
        if (in_array('+code', $flagsArray)) {
            return $this->renderCode($content, $language, $isCollapsible, $title);
        }

        // No flags = return null (let default renderer handle it)
        return null;
    }

    /**
     * Renders content with a live preview alongside optional code display.
     *
     * Handles the +preview flag by showing the rendered output first, then optionally
     * includes source code (+source) or generated HTML (+code) in either collapsible
     * or inline format based on configuration.
     */
    private function renderWithPreview(
        string $content,
        array $flagsArray,
        string $language,
        bool $isCollapsible,
        string $wrapperClass,
        string $title
    ): string {
        $rendered = '<div' . $wrapperClass . '>' . Blade::render($content) . '</div>';
        $codeLanguage = $this->getTorchlightLanguage($flagsArray, $language);

        $hasSource = in_array('+source', $flagsArray);
        $hasCode = in_array('+code', $flagsArray) || $this->getCodeLanguageOverride($flagsArray);

        if ($isCollapsible) {
            return $this->buildCollapsiblePreview($rendered, $content, $codeLanguage, $hasSource, $hasCode, $title);
        }

        return $this->buildInlinePreview($rendered, $content, $codeLanguage, $hasSource, $hasCode);
    }

    /**
     * Builds a collapsible preview with rendered output and optional code sections.
     *
     * Creates accordion-style sections showing the rendered preview followed by
     * collapsible source code and/or generated HTML based on the flags present.
     */
    private function buildCollapsiblePreview(
        string $rendered,
        string $content,
        string $codeLanguage,
        bool $hasSource,
        bool $hasCode,
        string $title
    ): string {
        $output = $rendered;

        if ($hasSource) {
            $output .= $this->buildCollapsibleSection($content, $codeLanguage, true, 'View Source', 'Copy Source');
        }

        if ($hasCode || ! $hasSource) {
            $generatedHtml = $this->formatHtml(Blade::render($content));
            $output .= $this->buildCollapsibleSection($generatedHtml, $codeLanguage, false, $title, 'Copy Code');
        }

        return $output;
    }

    /**
     * Builds an inline preview with rendered output and optional code blocks.
     *
     * Shows the rendered preview immediately followed by code blocks displayed
     * inline (not collapsible) for source and/or generated HTML based on flags.
     */
    private function buildInlinePreview(
        string $rendered,
        string $content,
        string $codeLanguage,
        bool $hasSource,
        bool $hasCode
    ): string {
        if ($hasSource) {
            $codeBlock = $this->renderCodeBlock($content, $codeLanguage, true);

            return $rendered . $codeBlock;
        }

        if ($hasCode) {
            $generatedHtml = $this->formatHtml(Blade::render($content));
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
    private function renderCode(string $content, string $language, bool $isCollapsible, string $title): string
    {
        if ($isCollapsible) {
            return $this->buildCollapsibleSection($content, $language, true, $title, 'Copy Code');
        }

        return $this->renderCodeBlock($content, $language, true);
    }

    /**
     * Extracts an attribute value from the flag string.
     *
     * Searches for attrName="value" (quoted) or attrName=value (unquoted) patterns
     * and returns the extracted value. When formatAsHtml is true, returns a formatted
     * HTML attribute string (e.g., ' class="value"'), otherwise returns just the raw value.
     * Returns null if the attribute is not found.
     */
    private function extractAttribute(string $flagString, string $attrName, bool $formatAsHtml = false): ?string
    {
        $escapedAttr = preg_quote($attrName, '/');

        // Quoted: attr="value" or attr='value'
        if (preg_match("/{$escapedAttr}=([\"'])(.+?)\\1/", $flagString, $matches)) {
            $value = $matches[2];

            return $formatAsHtml ? ' ' . $attrName . '="' . htmlspecialchars($value) . '"' : $value;
        }
        // Unquoted: attr=value
        if (preg_match("/{$escapedAttr}=(\\S+)/", $flagString, $matches)) {
            $value = $matches[1];

            return $formatAsHtml ? ' ' . $attrName . '="' . htmlspecialchars($value) . '"' : $value;
        }

        return null;
    }
}
