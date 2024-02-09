<?php

namespace Naykel\Gotime;

use Illuminate\Support\Facades\Route;

class RouteBuilder
{
    /**
     * @var array Data for view
     */
    protected array $data = [];

    /**
     * @var array|object Object of menus from json file
     */
    protected array|object $menus;

    /**
     * @var bool When set to true nav.json files will be cached
     */
    protected bool $cache = false;


    /**
     * RouteBuilder constructor.
     * @param string $filename The name of the JSON file to read the menu from.
     * @param string|null $layout The layout to use for the views.
     */
    public function __construct(
        protected string $filename,
        protected ?string $layout = null
    ) {
        $this->menus = $this->getMenusFromJson($filename);
        $this->data['filename'] = $filename;
    }

    /**
     * Create routes and views based on the menu data. This method iterates
     * through each menu item and generates corresponding routes and views.
     */
    public function create(): void
    {
        // Iterate over each menu item (link).
        foreach ($this->menus as $menu => $menuItem) {

            // Check if the menu item is standalone.
            if (!empty($menuItem->standalone_menu)) {
                $this->data['menus'] = [$menu];

                // Include additional menus if specified. This allows you to
                // display other unrelated menus.
                if (!empty($menuItem->include_menus)) {
                    foreach ($menuItem->include_menus as $menu) {
                        array_push($this->data['menus'], $menu);
                    }
                }
            } else {
                // Get all menu keys if the menu item is not standalone.
                $this->data['menus'] =  $this->getMenuKeys($this->menus);
            }

            // Process each link in the menu item.
            foreach ($menuItem->links as $item) {

                // Create child routes if specified.
                if (!empty($item->create_child_routes)) {
                    property_exists($item, 'children')
                        ? $this->createChildLinks($item->children)
                        : null;
                }

                // Create a route for the menu item.
                $this->make($item);
            }
        }
    }

    /**
     * Create routes and views for child items. This method processes child
     * items of a menu item and generates corresponding routes and views.
     * @param array|object $child The child items to process.
     */
    protected function createChildLinks(array|object $child): void
    {
        if (empty($child->exclude_route)) {
            foreach ($child as $item) {
                $this->make($item);
            }
        }
    }

    /**
     * Process a single menu item and create a route (if applicable).
     * @param array|object $item The menu item to process.
     */
    protected function make(array|object $item): void
    {
        if (!isset($item->exclude_route) || $item->exclude_route === false) {

            $url = toUrl($item->route_name ?? $item->url);

            $viewPath = empty($item->view) ?  $url : toUrl($item->view);

            // Determine the file type to inject into a layout. This is only
            // relevant for markdown files or when a layout is set, as
            // defining the type for a normal blade view is redundant.
            $this->data['type'] = ($item->type ?? null); // blade|md|null

            // ----------------------------------------------------------------
            // Note: The following conditional block is not a complete
            // solution because it limits you to a single markdown layout that
            // may or may not be suitable for all cases. In the future,
            // consider adding another parameter in the JSON file where you
            // could define a specific layout for markdown files.
            // ----------------------------------------------------------------

            // If the 'type' is set to 'md' (markdown), update the viewPath to
            // the location of the "markdown layout" which is
            // 'layouts.markdown'. Also, set the data['path'] to the location
            // of the actual markdown file to be injected into the layout.
            if (isset($item->type) && $item->type == 'md') {
                $this->data['path'] = $viewPath;
                $viewPath = 'components.layouts.markdown';
            }

            if ($this->layout) {
                $this->data['path'] = $viewPath;
                $viewPath = $this->layout;
            }

            $this->createRoute($url, $viewPath, ($item->route_name ?? null));
        };
    }

    /**
     * Create a Laravel route for the given URL and view path.
     * @param string $url The URL for the route.
     * @param string $view The view path for the route.
     * @param string|null $name The name of the route.
     */
    private function createRoute(string $url, string $view, string|null $name): void
    {
        $data = $this->data;

        Route::get($url, function () use ($data, $view) {
            return view($view)->with([$data, 'data' => $data]);
        })->name($name);
    }

    /**
     * Get the menu items from the json file
     * @param string $filename The name of the JSON file to read the menu from.
     * @return object The menu items from the JSON file.
     */
    protected function getMenusFromJson(string $filename): object
    {
        $file = getJsonFile(resource_path("navs/$filename.json"));

        return $this->cache ? cache()->remember($filename, 3600, fn () => $file) : $file;
    }

    /**
     * Get menu names (keys)
     * @param object $obj The object to get the keys from.
     * @return array The keys of the object.
     */
    private function getMenuKeys(object $obj): array
    {
        $menuKeys = [];

        foreach ($obj as $menu => $menuLinks) {
            array_push($menuKeys, $menu);
        }

        return $menuKeys;
    }
}
