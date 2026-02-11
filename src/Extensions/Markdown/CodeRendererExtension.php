<?php

namespace Naykel\Gotime\Extensions\Markdown;

use Illuminate\Support\Facades\Blade;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use Naykel\Gotime\Extensions\Markdown\Components\CodeBlockComponent;
use Naykel\Gotime\Extensions\Markdown\Components\CollapsibleCodeComponent;
use Naykel\Gotime\Extensions\Markdown\Components\DemoComponent;
use Naykel\Gotime\Extensions\Markdown\Components\PreviewComponent;
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
        $wrapperClass = $attributes['class'] ?? '';
        $title = $attributes['title'] ?? 'Show Code';

        $hasPreview = isset($attributes['preview']) || isset($attributes['demo']) || isset($attributes['demo-folded']);
        $hasCode = isset($attributes['code']) || isset($attributes['demo']) || isset($attributes['demo-folded']);
        $hasOutput = isset($attributes['output']);

        if ($hasPreview && $hasCode) {
            if ($hasOutput) {
                return DemoComponent::renderWithOutput($content, $content, $language, $isCollapsible, $wrapperClass, $title);
            }
            return DemoComponent::render($content, $content, $language, $isCollapsible, $wrapperClass, $title);
        }

        if ($hasPreview) {
            $previewHtml = PreviewComponent::render(Blade::render($content), $wrapperClass);
            if ($isCollapsible) {
                $codeHtml = CollapsibleCodeComponent::render('', $language, false, $title, 'Copy Output');
                return '<div class="demo-code-wrapper">' . $previewHtml . $codeHtml . '</div>';
            }
            return $previewHtml;
        }

        if ($hasCode) {
            $torchlight = new TorchlightRenderer;
            $codeLanguage = $torchlight->getLanguage($attributes, $language);

            if ($isCollapsible) {
                return CollapsibleCodeComponent::render($content, $codeLanguage, true, $title, 'Copy Code');
            }
            return CodeBlockComponent::render($content, $codeLanguage);
        }

        if ($hasOutput) {
            $htmlCleaner = new HtmlCleaner;
            $generatedHtml = $htmlCleaner->cleanAndFormat(Blade::render($content));
            return CodeBlockComponent::render($generatedHtml, $language);
        }

        return null;
    }
}
