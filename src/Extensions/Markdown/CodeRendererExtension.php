<?php

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
    use CodeRenderingTrait;

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, $this, 100);
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        /** @var FencedCode $node */
        $info = $node->getInfoWords();
        $language = $info[0] ?? 'html';
        $content = $node->getLiteral();

        // Legacy: +parse-mermaid (render mermaid diagrams)
        if (in_array('+parse-mermaid', $info)) {
            return $this->renderMermaid($content);
        }

        // New system: +render
        if (in_array('+render', $info)) {
            $rendered = '<div class="bx px-1 bdr-pink">' . Blade::render($content) . '</div>';

            // +render +source = show visual + original Blade source
            if (in_array('+source', $info)) {
                $codeLanguage = $this->getTorchlightLanguage($info, $language);
                $codeBlock = $this->renderCodeBlock($content, $codeLanguage, true);

                return $rendered . $codeBlock;
            }

            // +render +code = show visual + generated HTML
            if (in_array('+code', $info)) {
                $codeLanguage = $this->getTorchlightLanguage($info, $language);
                $generatedHtml = $this->formatHtml(Blade::render($content));
                $codeBlock = $this->renderCodeBlock($generatedHtml, $codeLanguage, false);

                return $rendered . $codeBlock;
            }

            return $rendered;
        }

        // Check for +code-X override first (e.g., +code-blade)
        $codeOverride = $this->getCodeLanguageOverride($info);
        if ($codeOverride) {
            return $this->renderCodeBlock($content, $codeOverride, true);
        }

        // Just +code = show highlighted code only
        if (in_array('+code', $info)) {
            return $this->renderCodeBlock($content, $language, true);
        }

        // No flags = return null (let default renderer handle it)
        return null;
    }

    private function renderMermaid(string $content): string
    {
        $content = trim($content);
        $safePath = ltrim($content, '/\\');
        $diagramPath = base_path($safePath);

        if (file_exists($diagramPath)) {
            $mermaidContent = file_get_contents($diagramPath);
        } else {
            $mermaidContent = $content;
        }

        $wrapped = "<x-mermaid>\n" . $mermaidContent . "\n</x-mermaid>\n";

        return Blade::render($wrapped);
    }
}
