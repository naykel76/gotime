<?php

namespace Naykel\Gotime\Extensions\Markdown\Component;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;
use Naykel\Gotime\Extensions\Markdown\Component\Parsing\ComponentBlock;
use Naykel\Gotime\Extensions\Markdown\Component\Parsing\ComponentStartParser;
use Naykel\Gotime\Extensions\Markdown\Component\Rendering\ComponentRenderer;
use Naykel\Gotime\Extensions\Markdown\Component\Rendering\StyleRegistry;
use Naykel\Gotime\Extensions\Markdown\Component\Styles\Box;
use Naykel\Gotime\Extensions\Markdown\Component\Styles\Button;
use Naykel\Gotime\Extensions\Markdown\Component\Styles\Card;
use Naykel\Gotime\Extensions\Markdown\Component\Styles\ShowMore;

/**
 * Component Extension for CommonMark.
 *
 * Adds support for custom markdown components using ::: syntax.
 *
 * Supported component types:
 * - collapse: Card-style collapsible section with border/shadow (perfect for FAQs)
 * - collapse-button: Simple button-style collapse without card styling
 * - box: Simple boxed content with optional title
 * - show-more: Minimal show more/less toggle for content under headings
 *
 * Example usage:
 *
 * ::: collapse title="Click to expand" opened
 * Hidden content here
 * :::
 *
 * ::: collapse-button title="Show More"
 * Minimal collapse style
 * :::
 *
 * ::: box title="Note" class="danger"
 * Important information
 * :::
 */
class ComponentExtension implements ExtensionInterface
{
    /**
     * Ensure styles are registered only once.
     */
    private static bool $stylesRegistered = false;

    public function register(EnvironmentBuilderInterface $environment): void
    {
        // Register component styles once
        if (! self::$stylesRegistered) {
            StyleRegistry::register('collapse', Card::class);
            StyleRegistry::register('collapse-button', Button::class);
            StyleRegistry::register('box', Box::class);
            StyleRegistry::register('show-more', ShowMore::class);
            self::$stylesRegistered = true;
        }

        // Register parsers and renderers
        $environment
            ->addBlockStartParser(new ComponentStartParser, 100)
            ->addRenderer(ComponentBlock::class, new ComponentRenderer, 100);
    }
}
