<?php

namespace Naykel\Gotime\DTO;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class MenuItemDTO
{

    /**
     * Menu item URL. This can be a full URL (including the 'https://' prefix)
     * for external links, or a relative URL for internal links.
     *
     * @var string
     */
    public string $url;

    /**
     * The menu item display name.
     * 
     * @var string
     */
    public string $name;

    /**
     * The children of the menu item.
     *
     * @var array|null
     */
    public ?array $children;

    /**
     * Indicates if the menu item is a parent.
     *
     * @var bool
     */
    public bool $isParent;

    /**
     * MenuItemDTO constructor.
     *
     * @param object $item The menu item.
     */
    public function __construct(object $item, private readonly string $menuName = 'apples')
    {
        if (!isset($item->name)) {
            throw new \InvalidArgumentException("There is an item in the '$menuName' menu with no name defined.");
        }

        $this->name = $item->name;
        $this->isParent = property_exists($item, 'children');
        $this->url = $this->handleUrl($item);
        $this->isParent ? $this->handleChildren($item->children) : $this->children = null;
    }

    /**
     * Handles the URL of the menu item.
     *
     * @param object $item The menu item.
     */
    protected function handleUrl(object $item): string
    {
        // if there is a URL set and it is an external link, use it as is and return
        if (isset($item->url) && $this->isExternalLink($item->url)) {
            return  $this->url = $item->url;
        }

        // if the item is a parent without a route or url you are safe to assume
        // it is a place holder so don't attempt to create a link, just exit.
        if ($this->isParent && !isset($item->route_name) && !isset($item->url)) return '';

        // I am not sure it is possible to get here without a route_name or url
        // set, but just in case, throw an exception.
        if (!isset($item->route_name) && !isset($item->url)) {
            throw new \Exception("There is no route name or url defined for the '$item->name' menu item in the '$this->menuName' menu");
        }

        return toPath($item->route_name ?? $item->url);
    }

    /**
     * Transforms each child of a parent item into a new instance of MenuItemDTO.
     * 
     * @param array $children The children of the parent item.
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
     * @param string $url The URL to check.
     * @return bool True if the URL is an external link, false otherwise.
     */
    protected function isExternalLink(string $url): bool
    {
        return Str::startsWith($url, 'http');
    }
}
