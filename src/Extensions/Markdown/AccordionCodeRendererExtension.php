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
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, $this, 105);
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        /** @var FencedCode $node */
        $info = $node->getInfoWords();
        $infoString = $node->getInfo();

        if (! in_array('+accordion', $info)) {
            return null;
        }

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

        $language = null;
        foreach ($torchlightLanguages as $flag => $lang) {
            if (in_array($flag, $info)) {
                $language = $lang;
                break;
            }
        }

        if (! $language) {
            return null;
        }

        $title = 'View Code';
        if (preg_match('/\+title=(["\'])(.+?)\1/', $infoString, $matches)) {
            $title = $matches[2];
        } elseif (preg_match('/\+title=(\S+)/', $infoString, $matches)) {
            $title = $matches[1];
        }

        $uniqueId = 'code-' . Str::random(8);
        $rawCode = htmlspecialchars($node->getLiteral());

        // Check if +parse-and-code flag is present
        $parseAndCode = in_array('+parse-and-code', $info);

        $parse = '<x-torchlight-code language="' . $language . '">' . $node->getLiteral() . '</x-torchlight-code>';

        // If +parse-and-code, render the output first
        $renderedOutput = '';
        if ($parseAndCode) {
            $renderedOutput = Blade::render($node->getLiteral());
        }

        $accordionWrapper = $renderedOutput . '
            <div x-data="{ open: false }" class="mt-05 mb">
                <div class="flex items-center gap-05">
                    <button x-on:click="open = !open" class="btn sm">
                        <span>' . htmlspecialchars($title) . '</span>
                    </button>
                    <button x-data="{ copied: false }"
                        @click="
                            const code = document.getElementById(\'' . $uniqueId . '\').getAttribute(\'data-code\');
                            if (navigator.clipboard && window.isSecureContext) {
                                navigator.clipboard.writeText(code);
                            } else {
                                const textarea = document.createElement(\'textarea\');
                                textarea.value = code;
                                textarea.style.position = \'fixed\';
                                textarea.style.left = \'-999999px\';
                                document.body.appendChild(textarea);
                                textarea.select();
                                document.execCommand(\'copy\');
                                document.body.removeChild(textarea);
                            }
                            copied = true;
                            setTimeout(() => copied = false, 2000);
                        "
                        class="btn sm"
                        :class="copied ? \'bg-sky-500\' : \'bg-sky-300\'"
                        x-text="copied ? \'Copied!\' : \'Copy\'" >
                    </button>
                </div>
                <div x-show="open" x-collapse class="mt-05">
                    <pre id="' . $uniqueId . '" data-code="' . $rawCode . '">' . Blade::render($parse) . '</pre>
                </div>
            </div>';

        return $accordionWrapper;
    }
}