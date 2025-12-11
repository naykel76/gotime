<?php

namespace Naykel\Gotime;

use Illuminate\Support\Facades\Route;
use Naykel\Gotime\DTO\RouteDTO;

class RouteBuilder
{
    /**
     * @var array Data passed to the view
     */
    protected array $data = [];

    /**
     * @var array|object Menu groups from JSON file
     */
    protected array|object $menus;

    /**
     * @var bool When set to true, nav.json files will be cached
     */
    protected bool $cache = false;

    /**
     * @var string Default layout for markdown content
     *             Use Laravel view notation: 'gotime::components.layouts.markdown' for package layout
     *             or 'components.layouts.markdown' for local layout
     */
    protected string $markdownLayout = 'gotime::components.layouts.markdown';

    /**
     * @param  string  $filename  The name of the JSON file to read routes from (without .json extension)
     * @param  string|null  $layout  Default layout for all views (uses Laravel view notation)
     *                               Examples: 'gotime::components.layouts.app' (package)
     *                               'components.layouts.app' (local)
     *                               'layouts.app' (local)
     */
    public function __construct(
        protected string $filename,
        protected ?string $layout = null
    ) {
        $this->menus = $this->getMenusFromJson($filename);
        $this->data['filename'] = $filename;
    }

    /**
     * Create routes from the navigation file. Iterates through all menu groups
     * and processes their links to generate Laravel routes.
     *
     * Note: The menu grouping structure (e.g., 'main', 'footer') is used for
     * organization in the JSON file but doesn't affect route generation.
     */
    public function create(): void
    {
        foreach ($this->menus as $menuName => $menu) {
            $this->data['menus'] = $this->getMenuNames($this->menus);
            $this->processLinks($menu->links);
        }
    }

    /**
     * Recursively processes route links from the JSON structure.
     *
     * For each link:
     * - Creates a route (unless 'exclude_route' is true)
     * - Recursively processes children (unless 'exclude_child_routes' is true)
     *
     * @param  array  $links  The links to process
     */
    protected function processLinks(array $links): void
    {
        foreach ($links as $routeItem) {
            // Skip import entries - they're handled by NavDTO for display only
            if (isset($routeItem->import)) {
                continue;
            }

            $item = new RouteDTO($routeItem);

            if ($item->excludeRoute === false) {
                $this->make($item);
            }

            // Check for child routes and process them recursively when applicable
            if ($item->isParent && empty($routeItem->exclude_child_routes)) {
                $this->processLinks($routeItem->children);
            }
        }
    }

    /**
     * Process a single route item and create the corresponding Laravel route.
     *
     * @param  RouteDTO  $item  The route item to process
     */
    protected function make(RouteDTO $item): void
    {
        $resolved = $this->resolveView($item);
        $routeData = array_merge($this->data, $resolved['data']);

        $this->createRoute($item->url, $resolved['view'], $item->routeName, $routeData);
    }

    /**
     * Resolve the view path and data for a route item.
     *
     * Resolution priority:
     * 1. Markdown type ('md') → uses $this->markdownLayout
     * 2. Per-item layout → uses $item->layout
     * 3. Constructor layout → uses $this->layout
     * 4. Direct view → uses $item->view directly
     *
     * When a layout is used, the original view path is passed as $data['path']
     * so the layout can render it.
     *
     * @param  RouteDTO  $item  The route item to resolve
     * @return array Contains 'view' (the blade file path) and 'data' (additional context)
     */
    protected function resolveView(RouteDTO $item): array
    {
        $viewPath = $item->view;
        $data = ['type' => $item->type ?? null];

        // Add layout options to data if present
        if (! empty($item->layoutOptions)) {
            $data['layoutOptions'] = $item->layoutOptions;
        }

        // Markdown files use the markdown layout
        if ($item->type === 'md') {
            $data['path'] = $viewPath;

            return [
                'view' => $this->markdownLayout,
                'data' => $data,
            ];
        }

        // Per-item layout override takes highest priority
        if ($item->layout) {
            $data['path'] = $viewPath;

            return [
                'view' => $item->layout,
                'data' => $data,
            ];
        }

        // Constructor layout is next priority
        if ($this->layout) {
            $data['path'] = $viewPath;

            return [
                'view' => $this->layout,
                'data' => $data,
            ];
        }

        // No layout: render the view directly
        return [
            'view' => $viewPath,
            'data' => $data,
        ];
    }

    /**
     * Create a Laravel route for the given URL and view path.
     *
     * @param  string  $url  The URL path for the route
     * @param  string  $view  The view path (using Laravel view notation)
     * @param  string|null  $name  The named route identifier
     * @param  array  $data  Data to pass to the view
     */
    private function createRoute(string $url, string $view, ?string $name, array $data): void
    {
        Route::get($url, function () use ($data, $view) {
            return view($view)->with(['data' => $data]);
        })->name($name);
    }

    /**
     * Get the menu groups from the JSON file.
     *
     * Reads from resources/navs/{$filename}.json and optionally caches the result.
     *
     * @param  string  $filename  The name of the JSON file (without .json extension)
     * @return object The menu groups from the JSON file
     */
    protected function getMenusFromJson(string $filename): object
    {
        $file = getJsonFile(resource_path("navs/$filename.json"));

        return $this->cache ? cache()->remember($filename, 3600, fn() => $file) : $file;
    }

    /**
     * Extract menu names (keys) from the navigation file object.
     *
     * @param  object  $obj  The menu object
     * @return array The menu names as an array of strings
     */
    private function getMenuNames(object $obj): array
    {
        return array_keys(get_object_vars($obj));
    }
}
