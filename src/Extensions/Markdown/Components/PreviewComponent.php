<?php

namespace Naykel\Gotime\Extensions\Markdown\Components;

class PreviewComponent
{
    public static function render(string $content, string $wrapperClass = ''): string
    {
        return '<div class="code-preview-container' . $wrapperClass . '">' . $content . '</div>';
    }
}
