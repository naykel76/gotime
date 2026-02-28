<?php

namespace Naykel\Gotime\Extensions\Markdown\Component\Rendering;

use Naykel\Gotime\Extensions\Markdown\Component\Styles\StyleInterface;

/**
 * Registry for component styles.
 *
 * Maps component type names (e.g., 'collapse', 'box') to style classes.
 * Allows easy registration and retrieval of style renderers.
 */
class StyleRegistry
{
    /**
     * Registered styles.
     *
     * @var array<string, class-string<StyleInterface>>
     */
    private static array $styles = [];

    /**
     * Register a style for a component type.
     *
     * @param  string  $type  Component type name (e.g., 'collapse', 'box')
     * @param  class-string<StyleInterface>  $styleClass  Style class name
     */
    public static function register(string $type, string $styleClass): void
    {
        self::$styles[$type] = $styleClass;
    }

    /**
     * Get a style instance for a component type.
     *
     * @param  string  $type  Component type name
     * @return StyleInterface|null Style instance or null if not found
     */
    public static function get(string $type): ?StyleInterface
    {
        if (! isset(self::$styles[$type])) {
            return null;
        }

        $styleClass = self::$styles[$type];

        return new $styleClass;
    }

    /**
     * Check if a style is registered for a type.
     */
    public static function has(string $type): bool
    {
        return isset(self::$styles[$type]);
    }

    /**
     * Get all registered style types.
     *
     * @return array<string>
     */
    public static function getRegisteredTypes(): array
    {
        return array_keys(self::$styles);
    }

    /**
     * Clear all registered styles (useful for testing).
     */
    public static function clear(): void
    {
        self::$styles = [];
    }
}
