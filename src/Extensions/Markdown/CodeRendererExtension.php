<?php
// https://www.youtube.com/watch?v=dt1ado9wJi8&t=739s&ab_channel=AaronFrancis
// https://aaronfrancis.com/2023/rendering-blade-components-in-markdown

namespace Naykel\Gotime\Extensions\Markdown;

use Illuminate\Support\Facades\Blade;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

class CodeRendererExtension implements ExtensionInterface, NodeRendererInterface
{

    // public static $allowBladeForNextDocument = false;

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, $this, 100);
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        /** @var FencedCode $node */
        $info = $node->getInfoWords();

        // if (!static::$allowBladeForNextDocument) return;

        if (in_array('+parse', $info)) {
            return Blade::render($node->getLiteral());
        }
    }
}
