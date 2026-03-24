<?php

namespace Naykel\Gotime\Extensions\Markdown\Components;

use Illuminate\Support\Facades\Blade;
use Naykel\Gotime\Extensions\Markdown\Services\HtmlCleaner;

class DemoComponent
{
    public static function render(
        string $bladeContent,
        string $code,
        string $language = 'html',
        bool $isCollapsible = false,
        string $wrapperClass = '',
        string $previewClass = '',
        string $title = 'Show Code'
    ): string {
        $previewHtml = PreviewComponent::render(Blade::render($bladeContent), $previewClass);
        $wrapperAttr = $wrapperClass ? ' class="' . $wrapperClass . '"' : '';

        if ($isCollapsible) {
            $codeHtml = CollapsibleCodeComponent::render($code, $language, true, $title, 'Copy Code');
        } else {
            $codeHtml = CodeBlockComponent::render($code, $language);
        }

        return '<div' . $wrapperAttr . '>' . $previewHtml . $codeHtml . '</div>';
    }

    public static function renderWithOutput(
        string $bladeContent,
        string $code,
        string $language = 'html',
        bool $isCollapsible = false,
        string $wrapperClass = '',
        string $previewClass = '',
        string $title = 'Show Code'
    ): string {
        $previewHtml = PreviewComponent::render(Blade::render($bladeContent), $previewClass);
        $wrapperAttr = $wrapperClass ? ' class="' . $wrapperClass . '"' : '';

        $generatedHtml = (new HtmlCleaner)->cleanAndFormat(Blade::render($bladeContent));

        if ($isCollapsible) {
            $codeSection = CollapsibleCodeComponent::render($code, $language, true, 'View Code', 'Copy Code');
            $outputSection = CollapsibleCodeComponent::render($generatedHtml, $language, false, $title, 'Copy Output');
        } else {
            $codeSection = CodeBlockComponent::render($code, $language);
            $outputSection = CodeBlockComponent::render($generatedHtml, $language);
        }

        return '<div' . $wrapperAttr . '>' . $previewHtml . $codeSection . $outputSection . '</div>';
    }
}
