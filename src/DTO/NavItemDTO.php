<?php

namespace Naykel\Gotime\DTO;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class NavItemDTO
{
    /**
     * Navigation item URL. This can be a full URL (including the 'https://' prefix)
     * for external links, or a relative URL for internal links.
     */
    public string $url;

    /**
     * The navigation item display name.
     */
    public string $name;

    /**
     * The children of the navigation item.
     */
    public ?array $children;

    /**
     * Indicates if the navigation item is a parent.
     */
    public bool $isParent;

    /**
     * The icon name to be used for the navigation item when withIcons is set to true.
     */
    public string $icon;

    /**
     * Optional description for the navigation item.
     *
     * This is not displayed by default but can be used to describe the page the
     * navigation item links to, provide a short summary of the item, etc.
     */
    public ?string $description;

    /**
     * NavItemDTO constructor.
     *
     * @param  object  $item  The navigation item.
     */
    public function __construct(object $item, private readonly string $menuName = '')
    {
        if (! isset($item->name)) {
            throw new \InvalidArgumentException("There is an item in the '$menuName' menu with no name defined.");
        }

        $this->name = $item->name;
        $this->icon = $item->icon ?? '';
        $this->description = $item->description ?? '';
        $this->isParent = property_exists($item, 'children');
        $this->url = $this->handleUrl($item);
        $this->isParent ? $this->handleChildren($item->children) : $this->children = null;
    }

    /**
     * Handles the URL of the navigation item.
     *
     * @param  object  $item  The navigation item.
     */
    protected function handleUrl(object $item): string
    {
        // if there is a URL set and it is an external link, use it as is and return
        if (isset($item->url) && $this->isExternalLink($item->url)) {
            return $this->url = $item->url;
        }

        // if the item is a parent without a route or url you are safe to assume
        // it is a place holder so don't attempt to create a link, just exit.
        if ($this->isParent && ! isset($item->route_name) && ! isset($item->url)) return '';

        // I am not sure it is possible to get here without a route_name or url
        // set, but just in case, throw an exception.
        if (! isset($item->route_name) && ! isset($item->url)) {
            throw new \Exception("There is no route name or url defined for the '$item->name' navigation item in the '$this->menuName' menu");
        }

        return $this->generateUrl($item);
    }

    /**
     * Generates the URL for the navigation item.
     *
     * @param  object  $item  The navigation item.
     * @return string The generated URL.
     */
    protected function generateUrl(object $item): string
    {
        if (isset($item->route_name)) {
            $this->validateRoute($item->route_name);

            return route($item->route_name, absolute: false);
        }

        return toPath($item->url);
    }

    /**
     * Validates that the route exists.
     *
     * @param  string  $route_name  The name of the route to validate.
     *
     * @throws \Exception If the route does not exist.
     */
    protected function validateRoute(string $route_name): void
    {
        if (! Route::has($route_name)) {
            throw new \Exception('Route ' . $route_name . ' does not exist.');
        }
    }

    /**
     * Transforms each child of a parent item into a new instance of NavItemDTO.
     *
     * @param  array  $children  The children of the parent item.
     */
    protected function handleChildren(array $children): void
    {
        $this->children = array_map(function ($child) {
            return new self($child);
        }, $children);
    }

    /**
     * Check if the URL is an external link.
     *
     * @param  string  $url  The URL to check.
     * @return bool True if the URL is an external link, false otherwise.
     */
    protected function isExternalLink(string $url): bool
    {
        return Str::startsWith($url, 'http');
    }
}
