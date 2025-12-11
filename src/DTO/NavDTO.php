<?php

namespace Naykel\Gotime\DTO;

use Illuminate\Support\Collection;

class NavDTO
{
    /**
     * NavDTO constructor.
     *
     * @param  Collection  $menuItems  Collection of navigation items.
     * @param  string  $menuName  The name of the navigation menu.
     */
    public function __construct(public Collection $menuItems, private string $menuName)
    {
        $this->menuItems = collect($menuItems->get('links'))
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
     * @param  object  $item  The import item with 'import' property in format "filename.menuname"
     * @return NavItemDTO A parent NavItemDTO with imported links as children
     */
    protected function resolveImport(object $item): NavItemDTO
    {
        $importPath = $item->import;
        $parts = explode('.', $importPath);

        if (count($parts) !== 2) {
            throw new \InvalidArgumentException("Import path must be in format 'filename.menuname'. Got: $importPath");
        }

        [$filename, $menuName] = $parts;

        // Load the referenced JSON file
        $file = getJsonFile(resource_path("navs/$filename.json"));

        if (! isset($file->$menuName)) {
            throw new \InvalidArgumentException("Menu '$menuName' not found in '$filename.json'");
        }

        $links = $file->$menuName->links ?? [];

        // Clean up the children links
        $children = array_map(function ($child) {
            unset($child->exclude_route);
            unset($child->create_child_routes);
            return $child;
        }, $links);

        // Create parent object with the imported children
        // Don't set route_name or url since this is just a dropdown parent
        $parentItem = (object) [
            'name' => $item->name ?? $this->formatMenuName($menuName),
            'icon' => $item->icon ?? '',
            'exclude_route' => true,
            'children' => $children,
        ];

        return new NavItemDTO($parentItem, $this->menuName);
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
