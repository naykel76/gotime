<?php

namespace Naykel\Gotime\Extensions\Markdown\Component\Rendering;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use Naykel\Gotime\Extensions\Markdown\Component\Parsing\ComponentBlock;
use Naykel\Gotime\Extensions\Markdown\Support\AttributeParser;

/**
 * Renders component blocks into HTML.
 *
 * Uses the StyleRegistry to delegate rendering to registered style classes.
 * Supported component types are defined by registered styles in ComponentExtension.
 */
class ComponentRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable|string
    {
        ComponentBlock::assertInstanceOf($node);

        /** @var ComponentBlock $node */
        $type = $node->getType();
        $attributesString = $node->getAttributesString();

        // Render child nodes (the content inside the component)
        $content = $childRenderer->renderNodes($node->children());

        // Parse attributes from attributes string (type is already extracted)
        $attributes = AttributeParser::parse($attributesString);

        // Return raw HTML directly - don't wrap in HtmlElement
        return $this->renderComponent($type, $content, $attributes);
    }

    /**
     * Render the appropriate component type using the StyleRegistry.
     * Falls back to 'collapse' style if the requested type is not registered.
     */
    private function renderComponent(string $type, string $content, array $attributes): string
    {
        $style = StyleRegistry::get($type);

        // Fallback to collapse style if type not found
        if ($style === null) {
            $style = StyleRegistry::get('collapse');
        }

        // If still null (shouldn't happen), throw descriptive error
        if ($style === null) {
            throw new \RuntimeException('No styles registered in StyleRegistry. Did you forget to call StyleRegistry::register() in ComponentExtension?');
        }

        return $style->render($content, $attributes);
    }
}
