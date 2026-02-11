<?php

namespace Naykel\Gotime\Extensions\Markdown\Components;

trait CopyButtonTrait
{
    protected static function generateCopyJs(string $elementId): string
    {
        return "
            const code = document.getElementById('{$elementId}-raw').value;
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(code);
            } else {
                const textarea = document.createElement('textarea');
                textarea.value = code;
                textarea.style.position = 'fixed';
                textarea.style.left = '-999999px';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }
            copied = true;
            setTimeout(() => copied = false, 2000);
        ";
    }
}
