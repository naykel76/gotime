<?php

namespace Naykel\Gotime\Extensions\Markdown;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

class AccordionCodeRendererExtension implements ExtensionInterface, NodeRendererInterface
{
    use CodeRenderingTrait;

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, $this, 105);
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        /** @var FencedCode $node */
        $info = $node->getInfoWords();
        $infoString = $node->getInfo();
        $language = $info[0] ?? 'html';
        $content = $node->getLiteral();

        // Must have +collapse flag
        if (! in_array('+collapse', $info)) {
            return null;
        }

        // Extract title if provided
        $title = 'View Code';
        if (preg_match('/\+title=(["\'])(.+?)\1/', $infoString, $matches)) {
            $title = $matches[2];
        } elseif (preg_match('/\+title=(\S+)/', $infoString, $matches)) {
            $title = $matches[1];
        }

        $renderedOutput = '';

        // If +render is present, render the component above the collapse
        if (in_array('+render', $info)) {
            $renderedOutput = '<div class="bx">' . Blade::render($content) . '</div>';
        }

        // Determine what to show in the collapse
        $hasSource = in_array('+source', $info);
        $hasCode = in_array('+code', $info) || $this->getCodeLanguageOverride($info);

        // Get Torchlight language
        $codeLanguage = $this->getTorchlightLanguage($info, $language);

        $accordionWrapper = $renderedOutput;

        // Build source section if +source is present
        if ($hasSource) {
            $sourceId = 'code-' . Str::random(8);
            $rawSource = htmlspecialchars($content);
            $renderedSource = $this->renderTorchlightCode($content, $codeLanguage, true);
            $copyJs = $this->getCopyButtonJs($sourceId);

            $accordionWrapper .= '
            <div x-data="{ open: false }" class="mt-05 mb">
                <div class="flex items-center gap-05">
                    <button x-on:click="open = !open" class="btn sm">
                        <span>View Source</span>
                    </button>
                    <button x-data="{ copied: false }" @click="' . $copyJs . '" class="btn sm"
                        :class="copied ? \'bg-sky-500\' : \'bg-sky-300\'"
                        x-text="copied ? \'Copied!\' : \'Copy Source\'">
                    </button>
                </div>
                <div x-show="open" x-collapse class="mt-05">
                    <pre id="' . $sourceId . '" data-code="' . $rawSource . '">' . $renderedSource . '</pre>
                </div>
            </div>';
        }

        // Build HTML section if +code is present or default
        if ($hasCode || ! $hasSource) {
            $codeId = 'code-' . Str::random(8);
            $generatedHtml = $this->formatHtml(Blade::render($content));
            $rawHtml = htmlspecialchars($generatedHtml);
            $renderedHtml = $this->renderTorchlightCode($generatedHtml, $codeLanguage, false);
            $copyJs = $this->getCopyButtonJs($codeId);

            $codeTitle = $title === 'View Code' ? $title : $title;

            $accordionWrapper .= '
            <div x-data="{ open: false }" class="mt-05 mb">
                <div class="flex items-center gap-05">
                    <button x-on:click="open = !open" class="btn sm">
                        <span>' . htmlspecialchars($codeTitle) . '</span>
                    </button>
                    <button x-data="{ copied: false }" @click="' . $copyJs . '" class="btn sm"
                        :class="copied ? \'bg-sky-500\' : \'bg-sky-300\'"
                        x-text="copied ? \'Copied!\' : \'Copy Code\'">
                    </button>
                </div>
                <div x-show="open" x-collapse class="mt-05">
                    <pre id="' . $codeId . '" data-code="' . $rawHtml . '">' . $renderedHtml . '</pre>
                </div>
            </div>';
        }

        return $accordionWrapper;
    }
}
