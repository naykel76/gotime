<?php

namespace Naykel\Gotime\Extensions\Markdown\Component\Parsing;

use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Cursor;

/**
 * Continues parsing a component block until the closing ::: is found.
 */
class ComponentParser extends AbstractBlockContinueParser
{
    private ComponentBlock $block;
    private int $indent;

    public function __construct(string $type, string $infoString, int $indent)
    {
        $this->block = new ComponentBlock($type, $infoString);
        $this->indent = $indent;
    }

    public function getBlock(): ComponentBlock
    {
        return $this->block;
    }

    public function isContainer(): bool
    {
        // This tells CommonMark that this block can contain other blocks
        return true;
    }

    public function canContain(AbstractBlock $childBlock): bool
    {
        // Allow any type of child block (paragraphs, lists, code blocks, etc.)
        return true;
    }

    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        // Check if we've hit the closing :::
        if ($cursor->getIndent() <= $this->indent && $cursor->match('/^:{3,}\\s*$/') !== null) {
            return BlockContinue::finished();
        }

        // Let child blocks be parsed - don't consume the line
        // This is key: we return at current cursor position so CommonMark can parse child blocks
        return BlockContinue::at($cursor);
    }
}
