<?php

namespace Naykel\Gotime\Extensions\Markdown;

use Illuminate\Support\Facades\Blade;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

class AccordionCodeRendererExtension implements ExtensionInterface, NodeRendererInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, $this, 105);
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        /** @var FencedCode $node */
        $info = $node->getInfoWords();
        $infoString = $node->getInfo(); // Get raw info string to parse title

        // Only handle code blocks with +accordion flag
        if (! in_array('+accordion', $info)) {
            return null; // Let other renderers handle it
        }

        // Define supported torchlight languages
        $torchlightLanguages = [
            '+torchlight-bash' => 'bash',
            '+torchlight-blade' => 'blade',
            '+torchlight-css' => 'css',
            '+torchlight-html' => 'html',
            '+torchlight-js' => 'js',
            '+torchlight-json' => 'json',
            '+torchlight-php' => 'php',
            '+torchlight-powershell' => 'powershell',
            '+torchlight-scss' => 'scss',
        ];

        // Find which torchlight language is being used
        $language = null;
        foreach ($torchlightLanguages as $flag => $lang) {
            if (in_array($flag, $info)) {
                $language = $lang;
                break;
            }
        }

        // If no torchlight language found, return null (let other renderers handle it)
        if (! $language) {
            return null;
        }

        // Extract title from +title="Your Title" or use default
        $title = 'View Code';
        if (preg_match('/\+title=(["\'])(.+?)\1/', $infoString, $matches)) {
            $title = $matches[2];
        } elseif (preg_match('/\+title=(\S+)/', $infoString, $matches)) {
            // Handle unquoted single word titles
            $title = $matches[1];
        }

        // Generate the highlighted code
        $parse = '<x-torchlight-code language="' . $language . '">' . $node->getLiteral() . '</x-torchlight-code>';

        $accordionWrapper = '
            <div x-data="{ open: false }" class="mt-05">
                <button x-on:click="open = !open" class="btn sm">
                    <span>' . htmlspecialchars($title) . "</span>
                </button>
                <div x-show=\"open\" x-collapse class=\"mt-05\">
                    <pre>" . Blade::render($parse) . '</pre>
                </div>
            </div>';

        return $accordionWrapper;
    }
}
