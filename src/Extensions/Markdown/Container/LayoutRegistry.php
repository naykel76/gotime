<?php

namespace Naykel\Gotime\Extensions\Markdown\Container;

use Naykel\Gotime\Extensions\Markdown\Container\Layouts\LayoutInterface;

/**
 * Registry for container layouts.
 *
 * Maps container type names (e.g., 'collapse', 'box') to layout classes.
 * Allows easy registration and retrieval of layout renderers.
 */
class LayoutRegistry
{
    /**
     * Registered layouts.
     *
     * @var array<string, class-string<LayoutInterface>>
     */
    private static array $layouts = [];

    /**
     * Register a layout for a container type.
     *
     * @param string $type Container type name (e.g., 'collapse', 'box')
     * @param class-string<LayoutInterface> $layoutClass Layout class name
     */
    public static function register(string $type, string $layoutClass): void
    {
        self::$layouts[$type] = $layoutClass;
    }

    /**
     * Get a layout instance for a container type.
     *
     * @param string $type Container type name
     * @return LayoutInterface|null Layout instance or null if not found
     */
    public static function get(string $type): ?LayoutInterface
    {
        if (!isset(self::$layouts[$type])) {
            return null;
        }

        $layoutClass = self::$layouts[$type];

        return new $layoutClass();
    }

    /**
     * Check if a layout is registered for a type.
     */
    public static function has(string $type): bool
    {
        return isset(self::$layouts[$type]);
    }

    /**
     * Get all registered layout types.
     *
     * @return array<string>
     */
    public static function getRegisteredTypes(): array
    {
        return array_keys(self::$layouts);
    }

    /**
     * Clear all registered layouts (useful for testing).
     */
    public static function clear(): void
    {
        self::$layouts = [];
    }
}
