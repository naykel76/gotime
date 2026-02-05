<?php

namespace Naykel\Gotime\Extensions\Markdown\Container;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

/**
 * Container Extension for CommonMark.
 *
 * Adds support for custom container blocks using ::: syntax.
 *
 * Supported container types:
 * - collapse: Collapsible sections with toggle button
 * - box: Simple boxed content with optional title
 *
 * Example usage:
 *
 * ::: collapse title="Click to expand" opened
 * Hidden content here
 * :::
 *
 * ::: box title="Note" class="danger"
 * Important information
 * :::
 */
class ContainerExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addBlockStartParser(new ContainerStartParser(), 100)
            ->addRenderer(ContainerBlock::class, new ContainerRenderer(), 100);
    }
}
