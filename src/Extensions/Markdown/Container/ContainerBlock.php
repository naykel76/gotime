<?php

namespace Naykel\Gotime\Extensions\Markdown\Container;

use League\CommonMark\Node\Block\AbstractBlock;

/**
 * Represents a container block in the markdown AST.
 *
 * Containers are defined with ::: syntax:
 *
 * ::: collapse title="Example"
 * Content here
 * :::
 */
class ContainerBlock extends AbstractBlock
{
    protected string $type;
    protected string $infoString;

    public function __construct(string $type, string $infoString)
    {
        parent::__construct();
        $this->type = $type;
        $this->infoString = $infoString;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getInfoString(): string
    {
        return $this->infoString;
    }
}
