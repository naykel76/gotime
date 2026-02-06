<?php

namespace Naykel\Gotime\Extensions\Markdown\Component\Parsing;

use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;

/**
 * Parses component blocks that start with :::
 *
 * Syntax:
 * ::: type attr="value" flag
 * Content...
 * :::
 */
class ComponentStartParser implements BlockStartParserInterface
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

        // Get the rest of the line (component type and attributes)
        $infoString = trim($cursor->getRemainder());

        // Extract component type (first word)
        $parts = preg_split('/\s+/', $infoString, 2);
        $type = $parts[0] ?? 'box'; // Default to 'box' if no type specified

        // Advance cursor to end of line to consume the opening tag
        $cursor->advanceToEnd();

        return BlockStart::of(new ComponentParser($type, $infoString, $indent))
            ->at($cursor);
    }
}
