<?php

namespace Naykel\Gotime\Extensions\Markdown\Container;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use Naykel\Gotime\Extensions\Markdown\Concerns\AttributeParsingTrait;
use Naykel\Gotime\Extensions\Markdown\Concerns\ContainerRenderingTrait;

/**
 * Renders container blocks into HTML.
 *
 * Supports different container types:
 * - collapse: Collapsible sections with Alpine.js
 * - box: Simple boxed content with optional title
 */
class ContainerRenderer implements NodeRendererInterface
{
    use AttributeParsingTrait;
    use ContainerRenderingTrait;

    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable|string
    {
        ContainerBlock::assertInstanceOf($node);

        /** @var ContainerBlock $node */
        $type = $node->getType();
        $infoString = $node->getInfoString();
        
        // Render child nodes (the content inside the container)
        $content = $childRenderer->renderNodes($node->children());

        // Parse attributes from info string
        $attributes = $this->parseAttributes($infoString);

        // Return raw HTML directly - don't wrap in HtmlElement
        return $this->renderContainer($type, $content, $attributes);
    }

    /**
     * Render the appropriate container type based on the type and attributes.
     */
    private function renderContainer(string $type, string $content, array $attributes): string
    {
        return match ($type) {
            'collapse' => $this->renderCollapseContainer($content, $attributes),
            'box' => $this->renderBoxContainer($content, $attributes),
            default => $this->renderBoxContainer($content, $attributes), // Default to box
        };
    }

    /**
     * Render a collapsible container.
     *
     * Attributes:
     * - title: Button label (default: "Show More")
     * - opened: Boolean flag to start expanded
     * - class: Additional CSS classes
     */
    private function renderCollapseContainer(string $content, array $attributes): string
    {
        $title = $attributes['title'] ?? 'Show More';
        $opened = isset($attributes['opened']);
        $classes = $attributes['class'] ?? '';

        return $this->buildCollapsibleWrapper($content, $title, $opened, $classes);
    }

    /**
     * Render a box container.
     *
     * Attributes:
     * - title: Optional title for the box
     * - class: Additional CSS classes
     */
    private function renderBoxContainer(string $content, array $attributes): string
    {
        $title = $attributes['title'] ?? null;
        $classes = $attributes['class'] ?? '';

        return $this->buildBox($content, $title, $classes);
    }
}
