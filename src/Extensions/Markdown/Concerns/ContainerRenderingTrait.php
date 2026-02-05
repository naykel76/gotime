<?php

namespace Naykel\Gotime\Extensions\Markdown\Concerns;

use Naykel\Gotime\Extensions\Markdown\Container\LayoutRegistry;

/**
 * Legacy trait for backward compatibility with code blocks.
 * 
 * This trait bridges old code block rendering methods to the new LayoutRegistry system.
 * New code should use LayoutRegistry directly.
 */
trait ContainerRenderingTrait
{
    /**
     * Legacy method - kept for backward compatibility with code blocks.
     * Bridges to the new LayoutRegistry system.
     */
    public function buildCollapsibleWrapper(
        string $content,
        string $title = 'Show More',
        bool $openByDefault = false,
        string $additionalClasses = ''
    ): string {
        $layout = LayoutRegistry::get('collapse');
        
        return $layout->render($content, [
            'title' => $title,
            'opened' => $openByDefault,
            'class' => $additionalClasses,
        ]);
    }

    /**
     * Build a collapsible container - bridges to LayoutRegistry.
     * 
     * @deprecated Use LayoutRegistry::get($type)->render() directly
     */
    public function buildCollapsible(
        string $content,
        string $title = 'Show More',
        bool $openByDefault = false,
        string $layout = 'card',
        string $additionalClasses = ''
    ): string {
        // Map old layout names to new registry keys
        $layoutMap = [
            'card' => 'collapse',
            'button' => 'collapse-button',
        ];
        
        $layoutKey = $layoutMap[$layout] ?? 'collapse';
        $layoutInstance = LayoutRegistry::get($layoutKey);
        
        return $layoutInstance->render($content, [
            'title' => $title,
            'opened' => $openByDefault,
            'class' => $additionalClasses,
        ]);
    }

    /**
     * Build a simple box container - bridges to LayoutRegistry.
     * 
     * @deprecated Use LayoutRegistry::get('box')->render() directly
     */
    public function buildBox(
        string $content,
        ?string $title = null,
        string $additionalClasses = ''
    ): string {
        $layout = LayoutRegistry::get('box');
        
        $attributes = ['class' => $additionalClasses];
        if ($title !== null) {
            $attributes['title'] = $title;
        }
        
        return $layout->render($content, $attributes);
    }
}
