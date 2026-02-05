<?php

namespace Naykel\Gotime\Extensions\Markdown\Container;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;
use Naykel\Gotime\Extensions\Markdown\Container\Layouts\Box;
use Naykel\Gotime\Extensions\Markdown\Container\Layouts\CollapseButton;
use Naykel\Gotime\Extensions\Markdown\Container\Layouts\CollapseCard;

/**
 * Container Extension for CommonMark.
 *
 * Adds support for custom container blocks using ::: syntax.
 *
 * Supported container types:
 * - collapse: Card-style collapsible section with border/shadow (perfect for FAQs)
 * - collapse-button: Simple button-style collapse without card styling
 * - box: Simple boxed content with optional title
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
class ContainerExtension implements ExtensionInterface
{
    /**
     * Ensure layouts are registered only once.
     */
    private static bool $layoutsRegistered = false;

    public function register(EnvironmentBuilderInterface $environment): void
    {
        // Register layout plugins once
        if (!self::$layoutsRegistered) {
            LayoutRegistry::register('collapse', CollapseCard::class);
            LayoutRegistry::register('collapse-button', CollapseButton::class);
            LayoutRegistry::register('box', Box::class);
            self::$layoutsRegistered = true;
        }
        
        // Register parsers and renderers
        $environment
            ->addBlockStartParser(new ContainerStartParser(), 100)
            ->addRenderer(ContainerBlock::class, new ContainerRenderer(), 100);
    }
}
