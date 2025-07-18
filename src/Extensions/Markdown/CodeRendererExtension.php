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
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, $this, 100);
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        /** @var FencedCode $node */
        // Get info words from the code block (e.g., language and flags) html
        // +parse,
        $info = $node->getInfoWords();

        if (in_array('+parse', $info)) {
            return Blade::render($node->getLiteral());
        }

        // torchlight codeblock wrap in <pre> tags
        if (in_array('+parse-code', $info)) {
            return '<pre>' . Blade::render($node->getLiteral()) . '</pre>';
        }

        // If +parse-mermaid is present, treat the code block content as a file path
        // relative to the project root (base_path). If the file exists, import its contents
        // as the Mermaid diagram. Otherwise, treat the code block content as an inline
        // Mermaid diagram. This allows both file-based and inline Mermaid diagrams.
        if (in_array('+parse-mermaid', $info)) {
            $content = trim($node->getLiteral());
            // Use base_path as the root, allow any relative path under project
            $safePath = ltrim($content, '/\\');
            $diagramPath = base_path($safePath);

            if (file_exists($diagramPath)) {
                $mermaidContent = file_get_contents($diagramPath);
            } else {
                $mermaidContent = $content; // fallback: treat as inline diagram
            }

            $wrappedContent = "<x-mermaid>\n" . $mermaidContent . "\n</x-mermaid>\n";
            return Blade::render($wrappedContent);
        }

        // Handle multiple torchlight languages in a DRY way
        $torchlightLanguages = [
            '+torchlight-php' => 'php',
            '+torchlight-css' => 'css',
            '+torchlight-scss' => 'scss',
            '+torchlight-blade' => 'blade',
            '+torchlight-html' => 'html',
            '+torchlight-js' => 'js',
            '+torchlight-bash' => 'bash',
        ];

        foreach ($torchlightLanguages as $flag => $language) {
            if (in_array($flag, $info)) {
                $parse = '<x-torchlight-code language="' . $language . '">' . $node->getLiteral() . '</x-torchlight-code>';

                return '<pre>' . Blade::render($parse) . '</pre>';
            }
        }

        /**
         * Display the code block with Torchlight syntax highlighting
         * and render the code itself as Blade output.
         */
        if (in_array('+parse-and-code', $info)) {
            // Wrap the code block in a Torchlight component for syntax highlighting
            $highlightedCode = '<x-torchlight-code language="' . $language . '">' . $node->getLiteral() . '</x-torchlight-code>';

            // Render the highlighted code block with Blade and wrap it in <pre> tags
            $renderedCodeBlock = '<pre>' . Blade::render($highlightedCode) . '</pre>';

            // Render the code itself as Blade output
            $renderedOutput = '<div class="bx">' . Blade::render($node->getLiteral()) . '</div>';

            // Return both the highlighted code and the rendered result
            return $renderedOutput . $renderedCodeBlock;
        }

        // This is a hacky way to parse and code js, but it works
        if (in_array('+parse-and-code-js', $info)) {
            $language = 'blade';
            // Wrap the code block in a Torchlight component for syntax highlighting
            $highlightedCode = '<x-torchlight-code language="' . $language . '">' . $node->getLiteral() . '</x-torchlight-code>';

            // Render the highlighted code block with Blade and wrap it in <pre> tags
            $renderedCodeBlock = '<pre>' . Blade::render($highlightedCode) . '</pre>';

            // Render the code itself as Blade output
            $renderedOutput = Blade::render($node->getLiteral());

            // Return both the highlighted code and the rendered result
            return $renderedOutput . $renderedCodeBlock;
        }
    }
}
