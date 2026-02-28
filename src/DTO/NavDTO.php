<?php

namespace Naykel\Gotime\DTO;

use Illuminate\Support\Collection;

class NavDTO
{
    public Collection $menuItems;

    /**
     * NavDTO constructor.
     *
     * @param  object  $menu  The raw menu object from the JSON file.
     * @param  string  $menuName  The name of the navigation menu.
     */
    public function __construct(object $menu, private string $menuName)
    {
        $this->menuItems = collect($menu->links ?? [])
            ->map(function ($item) {
                // Handle imports from other menu files
                if (isset($item->import)) {
                    return $this->resolveImport($item);
                }

                // remove properties not needed for navigation items to reduce clutter
                unset($item->exclude_route);
                unset($item->create_child_routes);

                return new NavItemDTO($item, $this->menuName);
            });
    }

    /**
     * Resolve an import reference to fetch menu items from another file.
     * Creates a parent item with the menu name and imported links as children.
     *
     * @param  object  $item  The import item with 'import' property in format "filename" or "filename.menuname"
     * @return NavItemDTO A parent NavItemDTO with imported links as children
     */
    protected function resolveImport(object $item): NavItemDTO
    {
        $importPath = $item->import;
        // Limit to 2 parts so menuname can contain dots (e.g. "nav.docs.components" → filename=nav, menuname=docs.components)
        $parts = explode('.', $importPath, 2);

        if (empty($parts[0])) {
            throw new \InvalidArgumentException("Import path must be in format 'filename' or 'filename.menuname'. Got: $importPath");
        }

        $filename = $parts[0];
        $menuName = $parts[1] ?? null;

        // Load the referenced JSON file
        $file = getJsonFile(resource_path("navs/$filename.json"));

        // If specific menu is provided with include_any, merge both
        if ($menuName && isset($item->include_any) && is_array($item->include_any)) {
            if (! isset($file->$menuName)) {
                throw new \InvalidArgumentException("Menu '$menuName' not found in '$filename.json'");
            }

            // Get the specified menu
            $links = $file->$menuName->links ?? [];

            // Filter links based on include/exclude
            $links = $this->filterLinks($links, $item);

            // Search other menus for include_any items
            $otherMenuItems = $this->searchOtherMenus($file, $menuName, $item->include_any);
            $links = array_merge($links, $otherMenuItems);
        }
        // If searching entire file for specific items (no menu specified)
        elseif (isset($item->include_any) && is_array($item->include_any) && ! $menuName) {
            $links = $this->searchAllMenus($file, $item->include_any);
        }
        // If specific menu is provided
        elseif ($menuName) {
            if (! isset($file->$menuName)) {
                throw new \InvalidArgumentException("Menu '$menuName' not found in '$filename.json'");
            }

            $links = $file->$menuName->links ?? [];

            // Filter links based on include/exclude
            $links = $this->filterLinks($links, $item);
        }
        // If no menu specified, import all menus
        else {
            $links = $this->getAllLinksFromFile($file);
        }

        // Add manual links from 'other' property if present
        if (isset($item->other) && is_array($item->other)) {
            $links = array_merge($links, $item->other);
        }

        // Clean up the children links
        $children = array_map(function ($child) {
            unset($child->exclude_route);
            unset($child->create_child_routes);

            return $child;
        }, $links);

        // Create parent object with the imported children
        // Don't set route_name or url since this is just a dropdown parent
        $parentItem = (object) [
            'name' => $item->name ?? $this->formatMenuName($menuName ?? $filename),
            'icon' => $item->icon ?? '',
            'exclude_route' => true,
            'children' => $children,
        ];

        return new NavItemDTO($parentItem, $this->menuName);
    }

    /**
     * Search all menus in a file for items matching the given names.
     *
     * @param  object  $file  The JSON file object
     * @param  array  $itemNames  Array of item names to search for
     * @return array Array of matching items
     */
    protected function searchAllMenus(object $file, array $itemNames): array
    {
        $foundItems = [];

        foreach ($file as $menuName => $menuData) {
            $links = $menuData->links ?? [];

            foreach ($links as $link) {
                if (in_array(strtolower($link->name), array_map('strtolower', $itemNames))) {
                    $foundItems[] = $link;
                }
            }
        }

        return $foundItems;
    }

    /**
     * Search other menus (excluding a specified menu) for items matching the given names.
     *
     * @param  object  $file  The JSON file object
     * @param  string  $excludeMenu  The menu name to exclude from search
     * @param  array  $itemNames  Array of item names to search for
     * @return array Array of matching items
     */
    protected function searchOtherMenus(object $file, string $excludeMenu, array $itemNames): array
    {
        $foundItems = [];

        foreach ($file as $menuName => $menuData) {
            // Skip the menu we're already including
            if ($menuName === $excludeMenu) {
                continue;
            }

            $links = $menuData->links ?? [];

            foreach ($links as $link) {
                if (in_array(strtolower($link->name), array_map('strtolower', $itemNames))) {
                    $foundItems[] = $link;
                }
            }
        }

        return $foundItems;
    }

    /**
     * Filter links based on include/exclude arrays.
     *
     * @param  array  $links  The links to filter
     * @param  object  $item  The import item with optional 'include' or 'exclude' arrays
     * @return array Filtered links
     */
    protected function filterLinks(array $links, object $item): array
    {
        // If include is specified, only return matching links
        if (isset($item->include) && is_array($item->include)) {
            return array_values(array_filter($links, function ($link) use ($item) {
                return in_array(strtolower($link->name), array_map('strtolower', $item->include));
            }));
        }

        // If exclude is specified, return all except matching links
        if (isset($item->exclude) && is_array($item->exclude)) {
            return array_values(array_filter($links, function ($link) use ($item) {
                return ! in_array(strtolower($link->name), array_map('strtolower', $item->exclude));
            }));
        }

        // No filter specified, return all links
        return $links;
    }

    /**
     * Get all links from all menus in a file.
     *
     * @param  object  $file  The JSON file object
     * @return array Array of all links from all menus
     */
    protected function getAllLinksFromFile(object $file): array
    {
        $allLinks = [];

        foreach ($file as $menuData) {
            $links = $menuData->links ?? [];
            $allLinks = array_merge($allLinks, $links);
        }

        return $allLinks;
    }

    /**
     * Format menu name to a readable title.
     *
     * @param  string  $menuName  The menu name to format
     * @return string Formatted menu name
     */
    protected function formatMenuName(string $menuName): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $menuName));
    }
}
