<?php

namespace Naykel\Gotime;

use Illuminate\Support\Facades\Route;
use Naykel\Gotime\DTO\RouteDTO;

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
     * @param string|null $layout default for all views in the menu
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
        foreach ($this->menus as $menuName => $menu) {
            $this->data['menus'] = $this->getMenuKeys($this->menus);
            $this->processLinks($menu->links);
        }
    }

    /**
     * Processes the links of a menu. If a link is a parent and has child routes,
     * it recursively calls itself to process the child links.
     * 
     * @param array $links The links of the menu to process.
     * @param string $menuName The name of the menu.
     * @return void
     */
    protected function processLinks(array $links): void
    {
        foreach ($links as $routeItem) {
            $item = new RouteDTO($routeItem);

            if ($item->excludeRoute === false) {
                $this->make($item);
            }

            // NK::TD find a way to override the exclude_child_routes and create
            // for a specific child route. Currently it is not possible and the
            // solution is not use exclude_child_routes in the parent route and
            // set the exclude_route to true in the child route.
            if ($item->isParent && empty($routeItem->exclude_child_routes)) {
                $this->processLinks($routeItem->children);
            }
        }
    }

    /**
     * Process a single menu item and create a route (if applicable).
     * @param array|object $item The menu item to process.
     */
    protected function make(RouteDTO $item): void
    {
        $url = $item->url;
        $routeName = $item->routeName;
        $viewPath = $item->view;

        $this->data['type'] = ($item->type ?? null); // blade|md|null

        // ----------------------------------------------------------------
        // Note: The following conditional block is not a complete solution
        // because it limits you to a single markdown layout that may or may
        // not be suitable for all cases. In the future, consider adding
        // another parameter in the JSON file where you could define a
        // specific layout for markdown files.
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
            // set the path to the file to be injected into the layout
            $this->data['path'] = $viewPath;
            // set the view to the default layout (template) for the view
            $viewPath = $this->layout;
        }

        $this->createRoute($url, $viewPath, $routeName);
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
     * 
     * @param object $obj The object to get the keys from.
     * @return array The keys of the object.
     */
    private function getMenuKeys(object $obj): array
    {
        return array_keys(get_object_vars($obj));
    }
}
