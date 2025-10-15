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
     * @var array|object Menu groups from json file
     */
    protected array|object $menus;

    /**
     * @var bool When set to true nav.json files will be cached
     */
    protected bool $cache = false;

    /**
     * @var string Layout to use for markdown content
     */
    protected string $markdownLayout = 'components.layouts.markdown';

    /**
     * @param  string  $filename  The name of the JSON file to read routes from.
     * @param  string|null  $layout  Default layout for all views
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
     * and processes their links to generate routes (the menu grouping itself
     * is ignored - we just need to process all links).
     */
    public function create(): void
    {
        foreach ($this->menus as $menuName => $menu) {
            $this->data['menus'] = $this->getMenuNames($this->menus);
            $this->processLinks($menu->links);
        }
    }

    /**
     * Processes route links. If a link is a parent and has children,
     * it recursively calls itself to process the child links.
     *
     * @param  array  $links  The links to process.
     */
    protected function processLinks(array $links): void
    {
        foreach ($links as $routeItem) {
            $item = new RouteDTO($routeItem);

            if ($item->excludeRoute === false) {
                $this->make($item);
            }

            if ($item->isParent && empty($routeItem->exclude_child_routes)) {
                $this->processLinks($routeItem->children);
            }
        }
    }

    /**
     * Process a single route item and create a route (if applicable).
     *
     * @param  RouteDTO  $item  The route item to process.
     */
    protected function make(RouteDTO $item): void
    {
        $resolved = $this->resolveView($item);
        $routeData = array_merge($this->data, $resolved['data']);

        $this->createRoute($item->url, $resolved['view'], $item->routeName, $routeData);
    }

    /**
     * Resolve the view path and data for a route item.
     * Priority: markdown type > constructor layout > default view path
     *
     * @param  RouteDTO  $item  The route item to resolve
     * @return array Contains 'view' (the blade file) and 'data' (additional context)
     */
    protected function resolveView(RouteDTO $item): array
    {
        $viewPath = $item->view;
        $data = ['type' => $item->type ?? null];

        if ($item->type === 'md') {
            $data['path'] = $viewPath;
            return [
                'view' => $this->markdownLayout,
                'data' => $data
            ];
        }

        if ($this->layout) {
            $data['path'] = $viewPath;
            return [
                'view' => $this->layout,
                'data' => $data
            ];
        }

        return [
            'view' => $viewPath,
            'data' => $data
        ];
    }

    /**
     * Create a Laravel route for the given URL and view path.
     *
     * @param  string  $url  The URL for the route.
     * @param  string  $view  The view path for the route.
     * @param  string|null  $name  The name of the route.
     * @param  array  $data  Data to pass to the view.
     */
    private function createRoute(string $url, string $view, ?string $name, array $data): void
    {
        Route::get($url, function () use ($data, $view) {
            return view($view)->with(['data' => $data]);
        })->name($name);
    }

    /**
     * Get the menu groups from the json file
     *
     * @param  string  $filename  The name of the JSON file to read routes from.
     * @return object The menu groups from the JSON file.
     */
    protected function getMenusFromJson(string $filename): object
    {
        $file = getJsonFile(resource_path("navs/$filename.json"));

        return $this->cache ? cache()->remember($filename, 3600, fn() => $file) : $file;
    }

    /**
     * Get menu names (keys) from the navigation file
     *
     * @param  object  $obj  The object to get the keys from.
     * @return array The keys of the object.
     */
    private function getMenuNames(object $obj): array
    {
        return array_keys(get_object_vars($obj));
    }
}
