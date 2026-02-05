<?php

namespace Naykel\Gotime\Extensions\Markdown\Container;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use Naykel\Gotime\Extensions\Markdown\Concerns\AttributeParsingTrait;

/**
 * Renders container blocks into HTML.
 *
 * Uses the LayoutRegistry to delegate rendering to registered layout classes.
 * Supported container types are defined by registered layouts in ContainerExtension.
 */
class ContainerRenderer implements NodeRendererInterface
{
    use AttributeParsingTrait;

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
     * Render the appropriate container type using the LayoutRegistry.
     * Falls back to 'collapse' layout if the requested type is not registered.
     */
    private function renderContainer(string $type, string $content, array $attributes): string
    {
        $layout = LayoutRegistry::get($type);
        
        // Fallback to collapse layout if type not found
        if ($layout === null) {
            $layout = LayoutRegistry::get('collapse');
        }
        
        // If still null (shouldn't happen), throw descriptive error
        if ($layout === null) {
            throw new \RuntimeException("No layouts registered in LayoutRegistry. Did you forget to call LayoutRegistry::register() in ContainerExtension?");
        }
        
        return $layout->render($content, $attributes);
    }
}
