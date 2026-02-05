<?php

namespace Naykel\Gotime\Extensions\Markdown\Container;

use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;

/**
 * Parses container blocks that start with :::
 *
 * Syntax:
 * ::: type attr="value" flag
 * Content...
 * :::
 */
class ContainerStartParser implements BlockStartParserInterface
{
    public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
    {
        // Check if line starts with :::
        if ($cursor->getNextNonSpaceCharacter() !== ':') {
            return BlockStart::none();
        }

        $indent = $cursor->getIndent();
        $fence = $cursor->match('/^:{3,}/');

        if ($fence === null) {
            return BlockStart::none();
        }

        // Get the rest of the line (container type and attributes)
        $infoString = trim($cursor->getRemainder());
        
        // Extract container type (first word)
        $parts = preg_split('/\s+/', $infoString, 2);
        $type = $parts[0] ?? 'box'; // Default to 'box' if no type specified
        
        return BlockStart::of(new ContainerParser($type, $infoString, $indent))
            ->at($cursor);
    }
}
