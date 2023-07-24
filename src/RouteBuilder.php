<?php

namespace Naykel\Gotime;

use Illuminate\Support\Facades\Route;

class RouteBuilder
{
    /**
     * Data for view
     */
    protected array $data = [];

    /**
     * object of menus from json file
     */
    protected array|object $menus;

    /**
     * when set to true nav.json files will be cached
     */
    protected bool $cache = false;

    public function __construct(
        protected string $filename,
        protected ?string $layout = null
    ) {
        $this->menus = $this->getMenusFromJson($filename);
        $this->data['filename'] = $filename;
        $this->data['menus'] =  $this->getMenuKeys($this->menus);

    }

    /**
     * Create routes and views based on the menu data. This method iterates
     * through each menu item and generates corresponding routes and views.
     */
    public function create(): void
    {
        foreach ($this->menus as $menu => $menuLinks) {

            foreach ($menuLinks->links as $item) {
                // check if child routes should be created.
                if (!empty($item->create_child_routes)) {
                    property_exists($item, 'children')
                        ? $this->createChildLinks($item->children)
                        : null;
                }

                $this->make($item);
            }
        }
    }

    /**
     * Create routes and views for child items. This method processes child
     * items of a menu item and generates corresponding routes and views.
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
     */
    protected function make(array|object $item): void
    {
        // Check if the menu item should be excluded from creating a route. If
        // 'exclude_route' is not set or is explicitly set to false, proceed.
        if (!isset($item->exclude_route) || $item->exclude_route === false) {

            // build url from `route_name` or use item `url` if null
            $url = toUrl($item->route_name ?? $item->url);

            // unless the `view` attribute has been defined, the builder will
            // attempt to resolve the view following the url structure
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
                $viewPath = 'layouts.markdown';
            }

            // In the context of a layout, the 'view' is the path to the template.
            if ($this->layout) {
                $this->data['path'] = $viewPath;
                $viewPath = $this->layout;
            }

            $this->createRoute($url, $viewPath, ($item->route_name ?? null));
        };
    }

    /**
     * Create a Laravel route for the given URL and view path.
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
     */
    protected function getMenusFromJson(string $filename): object
    {
        $file = getJsonFile(resource_path("navs/$filename.json"));

        return $this->cache ? cache()->remember($filename, 3600, fn () => $file) : $file;
    }

    /**
     * Get menu names (keys)
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
