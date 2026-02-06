<?php

namespace Naykel\Gotime\Extensions\Markdown\Component\Parsing;

use League\CommonMark\Node\Block\AbstractBlock;

/**
 * Represents a component block in the markdown AST.
 *
 * Components are defined with ::: syntax:
 *
 * ::: collapse title="Example"
 * Content here
 * :::
 */
class ComponentBlock extends AbstractBlock
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
