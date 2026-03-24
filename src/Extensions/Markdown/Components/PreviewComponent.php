<?php

namespace Naykel\Gotime\Extensions\Markdown\Components;

class PreviewComponent
{
    public static function render(string $content, string $previewClass = ''): string
    {
        $className = htmlspecialchars(trim('code-preview-container ' . $previewClass));

        return '<div class="' . $className . '">' . $content . '</div>';
    }
}
