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
        $infoString = $node->getInfo();
        $language = $info[0] ?? 'html';
        $content = $node->getLiteral();

        // Legacy: +parse-mermaid (render mermaid diagrams)
        if (in_array('+parse-mermaid', $info)) {
            return $this->renderMermaid($content);
        }

        // Check if we should use collapsible rendering
        $isCollapsible = in_array('+collapse', $info);
        
        // Extract class attribute if provided
        $wrapperClass = '';
        if (preg_match('/class=(["\'])(.+?)\1/', $infoString, $matches)) {
            $wrapperClass = ' class="' . htmlspecialchars($matches[2]) . '"';
        }

        // Extract title for collapsible sections
        $title = 'View Code';
        if (preg_match('/\+title=(["\'])(.+?)\1/', $infoString, $matches)) {
            $title = $matches[2];
        } elseif (preg_match('/\+title=(\S+)/', $infoString, $matches)) {
            $title = $matches[1];
        }

        // Handle +render flag
        if (in_array('+render', $info)) {
            $rendered = '<div' . $wrapperClass . '>' . Blade::render($content) . '</div>';
            $codeLanguage = $this->getTorchlightLanguage($info, $language);
            
            $hasSource = in_array('+source', $info);
            $hasCode = in_array('+code', $info) || $this->getCodeLanguageOverride($info);

            // If collapsible, build accordion sections
            if ($isCollapsible) {
                $output = $rendered;
                
                if ($hasSource) {
                    $output .= $this->buildCollapsibleSection($content, $codeLanguage, true, 'View Source', 'Copy Source');
                }
                
                if ($hasCode || !$hasSource) {
                    $generatedHtml = $this->formatHtml(Blade::render($content));
                    $output .= $this->buildCollapsibleSection($generatedHtml, $codeLanguage, false, $title, 'Copy Code');
                }
                
                return $output;
            }
            
            // Non-collapsible (inline)
            if ($hasSource) {
                $codeBlock = $this->renderCodeBlock($content, $codeLanguage, true);
                return $rendered . $codeBlock;
            }

            if ($hasCode) {
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
