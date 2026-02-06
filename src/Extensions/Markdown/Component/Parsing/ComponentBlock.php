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
    protected string $attributesString;

    public function __construct(string $type, string $attributesString)
    {
        parent::__construct();
        $this->type = $type;
        $this->attributesString = $attributesString;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the attributes string (everything after the component type).
     * Example: For "::: collapse title='Hello' opened", returns "title='Hello' opened"
     */
    public function getAttributesString(): string
    {
        return $this->attributesString;
    }
}
